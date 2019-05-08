<?php

return array(

    // Installation (comment after running)

    'install'               => 'Install:index',
    'install-verify'        => 'Install:verify',
    'install-verify-submit' => 'Install:verifySubmit',
    'install-wizard'        => 'Install:wizard',
    'install-wizard-2'      => 'Install:wizard2',
    'install-wizard-3'      => 'Install:wizard3',
    'uninstall'             => 'Install:uninstall',
    'uninstall-2'           => 'Install:uninstall2',

    // Configuration

    'config-get-settings'    => 'Config:getSettings',
    'config-update-settings' => 'Config:updateSettings',
    'config-reset-settings'  => 'Config:resetSettings',

    // Authentication

    'is-guest-logged-in' => 'Authentication:isGuestLoggedIn',
    'login-guest'        => 'Authentication:loginGuest',
    'login'              => 'Authentication:login',
    'logout'             => 'Authentication:logout',
    'logout-guest'       => 'Authentication:logoutGuest',

    // Common

    'get-default-avatars' => 'Operator:getDefaultAvatars',

    // Guest

    'guest-manage-connection'    => 'Guest:manageConnection',
    'guest-update-typing-status' => 'Guest:updateTypingStatus',
    'guest-get-typing-status'    => 'Guest:getTypingStatus',
    'guest-get-online'           => 'Guest:getOnline',

    // Operator

    'operator-manage-connection'    => 'Operator:manageConnection',
    'operator-update-typing-status' => 'Operator:updateTypingStatus',
    'operator-get-typing-status'    => 'Operator:getTypingStatus',
    'operator-update'               => 'Operator:update',
    'operator-upload-avatar'        => 'Operator:uploadAvatar',
    'operator-list'                 => 'Operator:list',
    'operator-create'               => 'Operator:create',
    'operator-delete'               => 'Operator:delete',
    'operator-get'                  => 'Operator:get',
    'operator-is-online'            => 'Operator:isOnline',
    'operator-online-users'         => 'Operator:getOnlineUsers',
    'operator-get-users'            => 'Operator:getUsers',
    'operator-invite-to-talk'       => 'Operator:inviteToTalk',
    'operator-leave-talk'           => 'Operator:leaveTalk',
    'operator-close-talk'           => 'Operator:closeTalk',
    'operator-transfer-talk'        => 'Operator:transferTalk',

    // Message

    'message-get-last'                => 'Message:getLast',
    'message-send'                    => 'Message:send',
    'message-guest-send'              => 'Message:guestSend',
    'message-get-history'             => 'Message:getHistory',
    'message-get-user-history'        => 'Message:getUserHistory',
    'message-query-history'           => 'Message:queryHistory',
    'message-clear-history'           => 'Message:clearHistory',
    'message-operator-guest-get-last' => 'Message:operatorGuestGetLast',
    'message-operator-get-talk'       => 'Message:getTalk',

    // Canned message

    'canned-message-create' => 'CannedMessage:create',
    'canned-message-get'    => 'CannedMessage:get',
    'canned-message-list'   => 'CannedMessage:list',
    'canned-message-update' => 'CannedMessage:update',
    'canned-message-delete' => 'CannedMessage:delete',

    // Department

    'department-create' => 'Department:create',
    'department-get'    => 'Department:get',
    'department-list'   => 'Department:list',
    'department-update' => 'Department:update',
    'department-delete' => 'Department:delete',
    'department-online' => 'Department:getOnline',

    // Contact

    'contact' => 'Contact:contact',

    // Widget

    'widget-iframe-content'     => 'Widget:iframeContent',
    'widget-inline'             => 'Widget:inlineView',
    'widget-mobile'             => 'Widget:mobileView',
    'widget-init.js'            => 'Widget:init',
    'widget-init-inline.js'     => 'Widget:initInline',
    'widget-custom-style.css'   => 'Widget:customStyle',
    'widget-theme-style.css'    => 'Widget:themeStyle',

    // Admin

    'admin'                  => 'Admin:index',
    'admin-logs'             => 'Admin:logs',
    'admin-clear-logs'       => 'Admin:clearLogs',
    'admin-update-blacklist' => 'Admin:updateBlacklist',
    'widget-test'            => 'Admin:widgetTest',

    // File sharing

    'upload'         => 'Upload:crud',
    'upload-upload'  => 'Upload:upload',
    'upload-deny'    => 'Upload:deny',
    'upload-confirm' => 'Upload:confirm',
    'upload-abort'   => 'Upload:abort',

    'shared-file-download' => 'SharedFile:download',

    // Online tracking

    'guest-track-keep-alive'    => 'OnlineGuests:keepAlive',
    'guest-track-login'         => 'OnlineGuests:login',
    'guest-track-confirm-login' => 'OnlineGuests:confirmLogin',
    'guest-track-list'          => 'OnlineGuests:list'
);

?>
