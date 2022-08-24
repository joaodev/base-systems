<?php

$router = [];

/*
 * App routes
 */
$router['app'] = [
	['namespace' => 'app', 'route' => '/', 'controller' => 'index', 'action' => 'index'],
	['namespace' => 'app', 'route' => '/inicio', 'controller' => 'index', 'action' => 'index'],
	
	['namespace' => 'app', 'route' => '/login', 'controller' => 'login', 'action' => 'index'],
	['namespace' => 'app', 'route' => '/esqueci-a-senha', 'controller' => 'login', 'action' => 'forgot-password'],
	['namespace' => 'app', 'route' => '/validar-codigo', 'controller' => 'login', 'action' => 'code-validation'],
	['namespace' => 'app', 'route' => '/alterar-senha', 'controller' => 'login', 'action' => 'change-password'],
	['namespace' => 'app', 'route' => '/auth',  'controller' => 'login', 'action' => 'auth'],
	['namespace' => 'app', 'route' => '/sair',  'controller' => 'login', 'action' => 'logout'],

	['namespace' => 'app', 'route' => '/usuarios', 'controller' => 'user', 'action' => 'index'],
	['namespace' => 'app', 'route' => '/usuarios/lista', 'controller' => 'user', 'action' => 'list'],
	['namespace' => 'app', 'route' => '/usuarios/detalhes', 'controller' => 'user', 'action' => 'read'],
	['namespace' => 'app', 'route' => '/usuarios/cadastrar', 'controller' => 'user', 'action' => 'create'],
	['namespace' => 'app', 'route' => '/usuarios/processa-cadastro', 'controller' => 'user', 'action' => 'create-process'],
	['namespace' => 'app', 'route' => '/usuarios/editar', 'controller' => 'user', 'action' => 'update'],
	['namespace' => 'app', 'route' => '/usuarios/processa-edicao', 'controller' => 'user', 'action' => 'update-process'],
	['namespace' => 'app', 'route' => '/usuarios/excluir', 'controller' => 'user', 'action' => 'delete'],
	['namespace' => 'app', 'route' => '/usuarios/valor-existente', 'controller' => 'user', 'action' => 'field-exists'],
	['namespace' => 'app', 'route' => '/usuarios/acl', 'controller' => 'user', 'action' => 'acl'],
	['namespace' => 'app', 'route' => '/usuarios/altera-permissao', 'controller' => 'user', 'action' => 'alter-privilege'],

	['namespace' => 'app', 'route' => '/controle-acesso', 'controller' => 'role', 'action' => 'index'],
	['namespace' => 'app', 'route' => '/controle-acesso/lista', 'controller' => 'role', 'action' => 'list'],
	['namespace' => 'app', 'route' => '/controle-acesso/detalhes', 'controller' => 'role', 'action' => 'read'],
	['namespace' => 'app', 'route' => '/controle-acesso/cadastrar', 'controller' => 'role', 'action' => 'create'],
	['namespace' => 'app', 'route' => '/controle-acesso/processa-cadastro', 'controller' => 'role', 'action' => 'create-process'],
	['namespace' => 'app', 'route' => '/controle-acesso/editar', 'controller' => 'role', 'action' => 'update'],
	['namespace' => 'app', 'route' => '/controle-acesso/processa-edicao', 'controller' => 'role', 'action' => 'update-process'],
	['namespace' => 'app', 'route' => '/controle-acesso/excluir', 'controller' => 'role', 'action' => 'delete'],
	['namespace' => 'app', 'route' => '/controle-acesso/valor-existente', 'controller' => 'role', 'action' => 'field-exists'],

	['namespace' => 'app', 'route' => '/controle-acesso/permissoes', 'controller' => 'privilege', 'action' => 'index'],
	['namespace' => 'app', 'route' => '/controle-acesso/altera-permissao', 'controller' => 'privilege', 'action' => 'change-privilege'],

	['namespace' => 'app', 'route' => '/configuracoes', 'controller' => 'config', 'action' => 'index'],
	['namespace' => 'app', 'route' => '/configuracoes/processa-edicao', 'controller' => 'config', 'action' => 'update-process'],

	['namespace' => 'app', 'route' => '/politica-privacidade', 'controller' => 'politics', 'action' => 'index'],
	['namespace' => 'app', 'route' => '/politica-privacidade/processa-edicao', 'controller' => 'politics', 'action' => 'update-process'],

	['namespace' => 'app', 'route' => '/logs', 'controller' => 'logs', 'action' => 'index'],
	
	['namespace' => 'app', 'route' => '/meu-perfil', 'controller' => 'my-profile', 'action' => 'index'],
	['namespace' => 'app', 'route' => '/meu-perfil/processa-edicao', 'controller' => 'my-profile', 'action' => 'update-process'],

	/* CustomerController */
	['namespace' => 'app', 'route' => '/clientes', 'controller' 				  => 'customers', 'action' => 'index'],
	['namespace' => 'app', 'route' => '/clientes/cadastrar', 'controller' 		  => 'customers', 'action' => 'create'],
	['namespace' => 'app', 'route' => '/clientes/processa-cadastro', 'controller' => 'customers', 'action' => 'create-process'],
	['namespace' => 'app', 'route' => '/clientes/detalhes', 'controller' 		  => 'customers', 'action' => 'read'],
	['namespace' => 'app', 'route' => '/clientes/editar', 'controller' 			  => 'customers', 'action' => 'update'],
	['namespace' => 'app', 'route' => '/clientes/processa-edicao', 'controller'   => 'customers', 'action' => 'update-process'],
	['namespace' => 'app', 'route' => '/clientes/excluir', 'controller' 		  => 'customers', 'action' => 'delete'],
	['namespace' => 'app', 'route' => '/clientes/valor-existente', 'controller'   => 'customers', 'action' => 'field-exists'],
	
];

switch ($_SERVER['HTTP_HOST']) {
    case 'localhost':
        $systemDir = "/base-systems";
        break;
    default:
        $systemDir = "";
        break;
}

$app = new Core\Init\Bootstrap($router, $systemDir);
