<?php
if (!defined('LCMS')) exit;

function view($slug) {

    $static = new StaticPage();
    $myStatic = $static->getStatic($slug);
    if($myStatic) {
        $template = new Template("pages/templates/page.php");
        $template->set('title',$myStatic['title']);
        $template->set('pageBody',$myStatic['html']);
        return $template->render();
    } else {
        return false;
    }

}

function create() {
    $auth = new Auth();
    $auth->restrictAccess(true);

    $title = $_GET['title'];
    $static = new StaticPage();
    $add = $static->addStatic($title);
    $response = array();
    if($add != FALSE) {
        $page = $static->getStatic($add);
        $response['url'] = $page['url'];
        $response['status'] = TRUE;
    } else {
        $response['status'] = FALSE;
    }

    return json_render($response);

}

function home() 
{
    $template = new Template("pages/templates/home.php");
 
    // Set our page variable "title" with the value "Hello World"
    $template->set('title', 'Homepage');
    $fbauth = new FBAuth();
    $isLoggedIn = $fbauth->isLoggedIn();
    $user = $fbauth->getUser();
    $user_profile = $fbauth->getUserProfile();
    $template->set('isLoggedIn',$isLoggedIn);
    $template->set('user',$user);
    $template->set('user_profile',$user_profile);
    $template->set('likes',$fbauth->graphQuery('likes/?limit=1000'));
    $template->set('media',$fbauth->getUserMedia());

    return $template->render();
}

