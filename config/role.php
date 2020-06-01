<?php
return [
    'list_permission' => array(
    	array(
            'name' => 'Users',
            'slug' => 'users',
            'list_sub' => array(
                array(
                    'name' => 'User Management',
                    'slug' => 'users management'
                )
            )
        ),
        array(
            'name' => 'Clients',
            'slug' => 'clients',
            'list_sub' => array(
                array(
                    'name' => 'Client Management',
                    'slug' => 'client management'
                ),
                array(
                    'name' => 'Client Contact List',
                    'slug' => 'client contact list'
                ),
                array(
                    'name' => 'Client Price List',
                    'slug' => 'client price list'
                ),
                array(
                    'name' => 'Client Location Contact List',
                    'slug' => 'client location contact list'
                ),
                array(
                    'name' => 'Client Location Agreements',
                    'slug' => 'client location agreements'
                ),
                array(
                    'name' => 'Client Location Price List',
                    'slug' => 'client location price list'
                )
            )
        ),
    	array(
            'name' => 'Matters',
            'slug' => 'matters',
    		'list_sub' => array(
                array(
                    'name' => 'Matter Management',
                    'slug' => 'matter management'
                ),
                array(
                    'name' => 'Matter Assignment',
                    'slug' => 'matter assignment'
                ),
                array(
                    'name' => 'Matter Notations',
                    'slug' => 'matter notations'
                ),
                array(
                    'name' => 'Files And Folders',
                    'slug' => 'files and folders'
                ),
                array(
                    'name' => 'Expenses',
                    'slug' => 'expenses'
                ),
                array(
                    'name' => 'Invoice & Billing',
                    'slug' => 'invoice and billing'
                ),
                array(
                    'name' => 'Reports',
                    'slug' => 'reports'
                )
            )
    	),
    	array(
    		'name' => 'Settings',
            'slug' => 'settings',
    		'list_sub' => array(
    			array(
                    'name' => 'Roles Managements',
                    'slug' => 'roles managements'
    			),
    			array(
                    'name' => 'Rates Managements',
                    'slug' => 'rates managements'
    			),
                array(
                    'name' => 'Offices',
                    'slug' => 'offices'
                ),
                array(
                    'name' => 'Types - Subtypes',
                    'slug' => 'types subtypes'
                ),
    			array(
                    'name' => 'Audit log',
                    'slug' => 'audit log'
                ),
                array(
                    'name' => 'Password policy',
                    'slug' => 'password policy'
                )
    		)
    	)
    )
];
