/*
  Lawsuit List
  -------------------
  Dependent on jQuery, mixItUp (Filter) & dropdown (Select Dropdowns)
*/

$( function() {
  'use strict';



  // Cache listContainer selectors
  var $listContainer = $('#list-container'),
      $listTiles = $('#list-container.container .mix'),
      ddCategories = '#select-categories',
      ddStatus = '#select-status';

  // Cache popover selectors
  var $popoverPending = $('.tile.status-lawsuits-pending li.status'),
      $popoverPendingHTML = $('.popover-status-lawsuits-pending'),
      $popoverInvestigating = $('.tile.status-under-investigation li.status'),
      $popoverInvestigatingHTML = $('.popover-status-under-investigation');

/*
  Applys the values of category and status
  then builds and applys a mixItUp filter
*/
  var applyFilter = function(){

      var filter = [],
        category = $selectCategories.val(),
          status = $selectStatus.val();

    /*
      concat the category to each status
      then push into the filter array
    */
      status = status.split(',');
      var statusLength = status.length;

      for (var i = 0; i < statusLength; i++) {
        // if no category is selected filter off status
        if(category == 'all'){
          category = '';
        }
        filter.push( category + status[i] );
      }

    // apply the title to the category
      filter.push('.title' + category);

    // console.log(filter.toString());

    // hide any popovers that maybe open
      $popoverPending.popover('hide');
      $popoverInvestigating.popover('hide');

    // run the filter
      $listContainer.mixItUp('filter', filter.toString() );
  };


/*
  Instantiate the DropDown menus.
  Listen for change events and apply the filter
*/
  $(ddCategories).dropdownselect();
  $(ddStatus).dropdownselect();

  // cache selectors from the dropdownselect DOM render
  var $selectCategories = $(ddCategories), $selectStatus = $(ddStatus);

  $selectCategories.change(function(){
      applyFilter();
  });

  $selectStatus.change(function(){
      applyFilter();
  });


/*
  Instantiate mixItUp.
  filters & sort events
*/
  $listContainer.mixItUp({
    controls: {
      toggleFilterButtons: true,
      enable: false
    },
    animation: {
      /*
        wait for mixItUp to load before applying
        animations for faster load times
      */
      enable: false
    },
    layout: {
     // display: 'block'
    },
    callbacks: {
      onMixLoad: function(){
        /*
          display the tiles even if JS has not loaded yet
          and then apply the hide class so sorting works
        */
        $listTiles.addClass('hide');

        $(this).mixItUp('setOptions', {
          animation: {
            enable: true,
            effects: 'fade stagger(50ms)',
            staggerSequence: function(i){
              return (2*i) - (5*((i/3) - ((1/3) * (i%3))));
            }
          },
        });
      },
      onMixFail: function(){
        console.log('No items were found.');
      }
    }
  });


/*
  Instantiate popovers
  for case statuses
*/
  $popoverPending.popover({
     trigger: 'click',
     placement: 'top',
     html: true,
     content: function(){
        return $popoverPendingHTML.html();
     }
  });
  $popoverInvestigating.popover({
     trigger: 'click',
     placement: 'top',
     html: true,
     content: function(){
        return $popoverInvestigatingHTML.html();
     }
  });


  // dismiss popover on body click
  $('body').on('click', function (e) {
      if ( !$(e.target).hasClass('status') && $(e.target).parents('.popover.in').length === 0) {
         $popoverPending.popover('hide');
         $popoverInvestigating.popover('hide');
      }
  });


}); // jQuery
