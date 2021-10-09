<?php

if(!function_exists("is_active")) {
    function is_active($status): bool
	{
        return (int)$status === 1;
    }
}

if(!function_exists("check")) {
    function is_logged_in(): bool
	{
        $auth = new Auth();

        return $auth->loginStatus();
    }
}

//if( has_role(['admin', 'editor']) ) {}
//if( has_role('subscriber') ) {}
if(!function_exists("has_roles")) {
    function has_roles($roles)
    {
        $auth = new Auth();

        return $auth->hasRole($roles);
    }
}

//if( can('edit-posts') ) {}
//if( can(['edit-posts', 'publish-posts']) ) {}
    if(!function_exists("has_permissions")) {
        function has_permissions($permissions)
        {
            $auth = new Auth();

            return $auth->can($permissions);
        }
    }
