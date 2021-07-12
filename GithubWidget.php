<?php


class GithubWidget extends WP_Widget
{

    //=================================================
    // Construtor
    //=================================================
    public function __construct()
    {
        parent::__construct(
            'githubWidget',
            'Bacciotti GitHub Widget API',
            array('description' => 'Mostrar sobre os usuários do Git.')
        );
    }

    //=================================================
    // O método "form" é o que é exibido no Dashboard
    //=================================================
    public function form($instance)
    {
        $user = isset($instance['user']) ? $instance['user'] : null;
        $qtd = isset($instance['qtd']) ? $instance['qtd'] : null;

        ?>
        <h4>Widget:</h4>
        <label>Usuário: </label><br>
        <input type="text" id="<?php echo $this->get_field_id('user'); ?>"
               name="<?php echo $this->get_field_name('user'); ?>" value="<?php echo $user; ?>"><br>
        <label>Quantidade: </label><br>
        <input type="text" id="<?php echo $this->get_field_id('qtd'); ?>"
               name="<?php echo $this->get_field_name('qtd'); ?>" value="<?php echo $qtd; ?>">
        <?php
    }

    //=================================================
    // O método "widget" é o que é exibido no FrontEnd, para o usuário
    //=================================================
    public function widget($args, $instance)
    {

        //=================================================
        // Função de request a API
        function gaps_convert_json($request_url)
        {
            $options = array("http" => array("user_agent" => $_SERVER['HTTP_USER_AGENT']));
            $context = stream_context_create($options);
            $response = file_get_contents($request_url, false, $context);
            $dados = json_decode($response);
            return $dados;
        }

        //=================================================
        $user = $instance['user'];
        $qtd = $instance['qtd'];
        $request_url = "https://api.github.com/users/" . $user . "/repos?sort=createdDate&per_page=" . $qtd;
        $repos = gaps_convert_json($request_url);
        $request_url = "https://api.github.com/users/" . $user;
        $usuario = gaps_convert_json($request_url);

        ?>

        <div class="usuario">
            <hr>
            <img src="<?php echo $usuario->avatar_url ?>"><br>
            <span>Repositórios</span><br>
            <span>Nickname:</span> <?php echo $usuario->name; ?><br>
            <?php if (isset($usuario->email)) {
                ?>
                <span>Email:</span> <?php echo $usuario->email; ?>
                <?php
            }
            ?>
            <hr>
        </div>

        <?php foreach ($repos as $repo): ?>
        <?php echo $repo->name ?>
        Linguagem: <?php echo $repo->language ?>
        <a href="<?php echo $repo->html_url ?>">
            <button>Ver</button>
        </a>

    <?php endforeach; ?>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['user'] = !empty($new_instance['user']) ? strip_tags($new_instance['user']) : '';
        $instance['qtd'] = !empty($new_instance['qtd']) ? strip_tags($new_instance['qtd']) : '';
        return $instance;
    }
}