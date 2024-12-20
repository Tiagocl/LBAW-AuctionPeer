document.addEventListener('DOMContentLoaded', function () {
    const dropdownToggle = document.getElementById('dropdownToggle');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const dropdownWrapper = document.getElementById('dropdownWrapper');

    dropdownToggle.addEventListener('click', function (e) {
        e.stopPropagation();
         dropdownMenu.style.display = 'block';
         dropdownWrapper.style.borderBottomRightRadius = '0';
        console.log('Dropdown menu initialized');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function () {
        dropdownMenu.style.display = 'none';
        dropdownWrapper.style.borderBottomRightRadius = '10px';
    });

    
});