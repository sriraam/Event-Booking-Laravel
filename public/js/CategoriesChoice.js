/*
  categoriesChoice.js
  Custom initialization for multi-category selects.
  Uses Choices.js library (MIT license).
  Choices.js repo: https://github.com/Choices-js/Choices
*/

document.addEventListener('DOMContentLoaded', function () {
    const selects = document.querySelectorAll('.multi-category');
  
    selects.forEach(ele => {
      if (typeof Choices !== 'undefined') {
        new Choices(ele, {
          removeItemButton: true,
          searchEnabled: true,
          shouldSort: false,
          placeholder: true,
          placeholderValue: 'Select categories',
          itemSelectText: ''
        });
      }
    });
  });