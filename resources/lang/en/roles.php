<?php

return [
    'titles' => [
        'view users'                => 'view users',
        'create users'              => 'create users',
        'edit users'                => 'edit users',
        'delete users'              => 'delete users',
        'view clients and client locations'   => 'view clients and client locations',
        'create clients and client locations' => 'create clients and client locations',
        'edit client and client locations information' => 'edit client and client locations information',
        'delete clients and client locations' => 'delete clients and client locations',
        'view client contacts'      => 'view client contacts',
        'create client contacts'    => 'create client contacts',
        'edit client contacts'      => 'edit client contacts',
        'delete client contacts'    => 'delete client contacts',
        'view client price list'    => 'view client price list',
        'edit client price list'    => 'edit client price list',
        'view client location contacts'   => 'view client location contacts',
        'create client location contacts' => 'create client location contacts',
        'edit client location contacts'   => 'edit client location contacts',
        'delete client location contacts' => 'delete client location contacts',
        'view agreements'           => 'view agreements',
        'upload agreements'         => 'upload agreements',
        'edit agreements'           => 'edit agreements',
        'delete agreements'         => 'delete agreements',
        'view client location price list' => 'view client location price list',
        'edit client location price list' => 'edit client location price list',
        'view matters'              => 'view matters',
        'create matters'            => 'create/ reopen matters',
        'edit matters information'   => 'edit matters information',
        'delete matters'            => 'delete matters',
        'edit matters milestone'    => 'edit matters milestone',
        'due date'                  => 'due date',
        'internal due date'         => 'internal due date',
        'date received'             => 'date received',
        'date of referral'          => 'date of referral',
        'date invoiced'             => 'date invoiced',
        'date report sent'          => 'date report sent',
        'date file returned'        => 'date file returned',
        'date interim report sent'  => 'date interim report sent',
        'edit matter status'        => 'edit matter status',
        'close matter'              => 'close matter',
        'view matter assignments'   => 'view matter assignments',
        'assign users to matters'   => 'assign/ unassign users to matters',
        'view notations'            => 'view notations',
        'upload notations'          => 'upload notations',
        'edit notations'            => 'edit notations',
        'delete notations'          => 'delete notations',
        'view files and folders'    => 'view files and folders',
        'upload files and create folders' => 'upload files and create folders',
        'edit files and folders'    => 'edit files and folders',
        'delete files and folders'  => 'delete files and folders',
        'view expenses'             => 'view expenses',
        'create and submit expense' => 'create and submit expense',
        'approve expenses entries'  => 'approve/ decline expenses entries',
        'edit expenses'             => 'edit expenses',
        'delete expenses'           => 'delete expenses',
        'view invoice and billing'  => 'view invoice & billing',
        'create invoices'           => 'create invoices',
        'manage payments for invoices' => 'manage payments for invoices',
        'send invoices'             => 'send invoices',
        'edit invoices'             => 'edit invoices',
        'delete invoices'           => 'delete invoices',
        'view invoice and billing reports' => 'view invoice & billing',
        'create invoices reports'   => 'create invoices',
        'manage payments for invoices reports' => 'manage payments for invoices',
        'send invoices reports'     => 'send invoices',
        'edit invoices reports'     => 'edit invoices',
        'delete invoices reports'   => 'delete invoices',
        'view roles'                => 'view',
        'create roles'              => 'create',
        'edit roles'                => 'edit',
        'delete roles'              => 'delete',
        'view rates'                => 'view',
        'create rates'              => 'create',
        'edit rates'                => 'edit',
        'delete rates'              => 'delete',
        'view offices'              => 'view',
        'create offices'            => 'create',
        'edit offices'              => 'edit',
        'delete offices'            => 'delete',
        'view types'                => 'view',
        'create types'              => 'create',
        'edit types'                => 'edit',
        'delete types'              => 'delete',
        'allow to view audit log'   => 'allow to view audit log',
        'allow setting'             => 'allow setting',
    ],
    'notes' => [
        'view users'                => 'Grants access to view user list and user detail information. Some information may not be available based on additional permissions.',
        'create users'              => 'Grants access to create new user. A user should have the permission to "View User" to activate this permission.',
        'edit users'                => 'A user can still edit their own profile, even if the ability to edit user is disabled.',
        'delete users'              => 'Permits a user to delete user profiles. A user can only delete a user profile on a lesser ranked user, regardless of permissions.',
        'view clients and client locations'   => 'Provides access to your client list, client location list and view detail of client or client location. A user can still view the client and client location on a matter he is assigned to, even without this permission.',
        'create clients and client locations' => 'Allows a user to create a new client and new client location. A user should have access to "View Clients And Client Locations" in order to activate this permission.',
        'edit client and client locations information' => 'Permits a user to make changes to client info and client location information.',
        'delete clients and client locations' => 'A user can only delete clients and Client locations that they have access to view detail.',
        'view client contacts'      => 'Grants access to view all client contacts associated with clients in the system. It is the “Contact List” tab on Client Detail. A user should have access to “View Clients And Client Locations” to activate this permission.',
        'create client contacts'    => 'Allow a user to create a new client contacts on an existing client. A user should have access to “View Client Contacts” to activate this permission.',
        'edit client contacts'      => 'Permits a user to make changes to client contact in “Contact List” tab on Client Detail.',
        'delete client contacts'    => 'A user can only delete client contacts that they have access to view detail.',
        'view client price list'    => 'Permits a user to view the “Price List” tab on Client Detail.',
        'edit client price list'    => 'Grants access for a user to make custom price list. A user should have access to "View Client Price List" in order to activate this permission.',
        'view client location contacts'   => 'Grants access to view all client contacts associated with clients in the system. It is the “Contact List” tab on Client Location Detail. A user should have access to “View Clients And Client Locations” to activate this permission.',
        'create client location contacts' => 'Allow a user to create a new client location contacts on an existing client. A user should have access to “View Client Location Contacts” to activate this permission.',
        'edit client location contacts'   => 'Permits a user to make changes to client location contact in “Contact List” tab on Client Detail.',
        'delete client location contacts' => 'A user can only delete client location contacts that they have access to view detail.',
        'view agreements'           => 'Permits a user to view the “Agreement” tab on Client Location Detail.',
        'upload agreements'         => 'Grants a user the ability to upload new Agreement for a client location. A user should have the permission to “View Agreements” to activate this permission.',
        'edit agreements'           => 'Permits a user to make changes to upload files.',
        'delete agreements'         => 'A user can only delete agreement files that they have access to view.',
        'view client location price list' => 'Permits a user to view the “Price List” tab on Client Location Detail.',
        'edit client location price list' => 'Grants access for a user to make custom price list. A user should have access to "View Client Location Price List" in order to activate this permission.',
        'view matters'              => 'Limited access users are restricted to viewing their own matters.',
        'create matters'            => 'Permits a user to create a new matter, or reopen an existing matter. Limited access users will automatically be assigned to matters they create themselves.',
        'edit matters information'              => 'Permits a user to make changes the general information of a matter (Client-Location, Type, Office) and add or edit the tab “Information” on Matter Detail.',
        'delete matters'            => 'A user can only delete matter that they have access to view detail.',
        'edit matters milestone'    => 'Permits a user to make changes the milestone of a matter.',
        'due date'                  => '',
        'internal due date'         => '',
        'date received'             => '',
        'date of referral'          => '',
        'date invoiced'             => '',
        'date report sent'          => '',
        'date file returned'        => '',
        'date interim report sent'  => '',
        'edit matter status'        => 'Provides the ability to change the status of a matter.',
        'close matter'              => 'Provides the ability to close matters.',
        'view matter assignments'   => 'Permits a user to view the “Assignees” on matters, which displays all users assigned to a matter.',
        'assign users to matters'   => 'Permits a user to assign users to a matter. A user should have access to “View Matter Assignments” to activate this permission.',
        'view notations'            => 'Grants access to view the “Notations” tab on Matter Detail. A user should have access to “View Matters” to activate this permission.',
        'upload notations'          => 'Grants a user the ability to upload new Notation for a matter. A user should have the permission to “View Notations” to activate this permission.',
        'edit notations'            => 'Permits a user to make changes to upload notations.',
        'delete notations'          => 'A user can only delete notations that they have access to view.',
        'view files and folders'    => 'Permits a user to view the “Files” tab on a matter. A user should have access to “View Matters” to activate this permission.',
        'upload files and create folders' => 'Grants a user the ability to upload new files and create folders on a matter. A user must have the permission to View Files And Folders in order to view these files.',
        'edit files and folders'    => 'Permits a user to make changes to upload files (e.g. the name of the file)',
        'delete files and folders'  => 'A user can only delete files and folders that they have access to view.',
        'view expenses'             => 'Permits a user to view the “Expenses” tab on a matter. A user should have access to “View Matters” to activate this permission.',
        'create and submit expense' => 'Grants a user the ability to create expenses on a matter. A user must have the permission to “View Expenses” in order to create expenses.',
        'approve expenses entries'  => 'Permits a user to “Approve” or “Decline” expense entries that have been submitted for approval.',
        'edit expenses'             => 'Permits a user to make changes to submitted expenses.',
        'delete expenses'           => 'A user can only delete expense entries that they have access to view.',
        'view invoice and billing'  => 'Permits a user to view the “Invoice & Billing” tab on a matter. A user should have access to “View Matters” to activate this permission.',
        'create invoices'           => 'Permits a user to create a new invoice. A user should have permission to “View Invoice & Billing” to activate this permission.',
        'manage payments for invoices' => 'Permits a user to apply payments on an outstanding invoice. To activate this permission, the user must have the ability to “View Invoice & Billing”.',
        'send invoices'             => 'Allows a user to send an invoice and/ or an overdue reminder email to the client contact.',
        'edit invoices'             => 'Allows a user to make changes to existing invoices. This ability includes the ability to add or remove line items from invoices.',
        'delete invoices'           => 'A user can only delete invoices that they have access to view.',
        'view invoice and billing reports' => '',
        'create invoices reports'   => '',
        'manage payments for invoices reports' => '',
        'send invoices reports'     => '',
        'edit invoices reports'     => '',
        'delete invoices reports'   => '',
        'view roles'                => '',
        'create roles'              => '',
        'edit roles'                => '',
        'delete roles'              => '',
        'view rates'                => '',
        'create rates'              => '',
        'edit rates'                => '',
        'delete rates'              => '',
        'view offices'              => '',
        'create offices'            => '',
        'edit offices'              => '',
        'delete offices'            => '',
        'view types'                => '',
        'create types'              => '',
        'edit types'                => '',
        'delete types'              => '',
        'allow to view audit log'   => '',
        'allow setting'             => '',
    ],
];
