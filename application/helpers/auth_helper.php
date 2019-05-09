<?php

if(! function_exists("is_active")) {
    function is_active($status)
    {
        return (int)$status === 1 ? true : false;
    }
}

if(! function_exists("check")) {
    function check()
    {
        $auth = new Auth();
        return $auth->loginStatus();
    }
}

//if( can('edit-posts') ) {}
//if( can(['edit-posts', 'publish-posts']) ) {}
if(! function_exists("can")) {
    function can($permissions)
    {
        $auth = new Auth();
        return $auth->can($permissions);
    }
}

//if( has_role(['admin', 'editor']) ) {}
//if( has_role('subscriber') ) {}
if(! function_exists("has_role")) {
    function has_role($roles)
    {
        $auth = new Auth();
        return $auth->hasRole($roles);
    }
}
