import './bootstrap';

import Alpine from 'alpinejs';
import './bootstrap';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// 初始化所有下拉選單
document.addEventListener('DOMContentLoaded', function() {
    // 獲取所有下拉觸發元素
    var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });

    // 也可以手動初始化特定選單
    var adminDropdown = document.getElementById('adminDropdown');
    if (adminDropdown) {
        new bootstrap.Dropdown(adminDropdown);
    }

    var userDropdown = document.getElementById('navbarDropdown');
    if (userDropdown) {
        new bootstrap.Dropdown(userDropdown);
    }

    var systemManagementDropdown = document.getElementById('systemManagementDropdown');
    if (systemManagementDropdown) {
        new bootstrap.Dropdown(systemManagementDropdown);
    }
});

// 如果需要全局可用的 Bootstrap
window.bootstrap = bootstrap;
