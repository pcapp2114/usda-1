(function ($, Drupal, drupalSettings) {
  //console.log(drupalSettings);
  Drupal.behaviors.quickStats = {
    attach: function (context, settings) {
      
      // $(document).ready(function () {
      //   init();
      //   console.log(document);
      // });

      var data;
      var baseurl = window.location.origin;
      var paramsURL = baseurl + drupalSettings.quickStats.params;
      var groupsURL = baseurl + drupalSettings.quickStats.groups;
      var commodityURL = baseurl + drupalSettings.quickStats.commodities; 
      var resultsURL = baseurl + drupalSettings.quickStats.results;
      var shortdescURL = baseurl + drupalSettings.quickStats.shortdesc;
      var uuiddecodeURL = baseurl + drupalSettings.quickStats.uuiddecode;
      var sectorName;
      var groupName;
      var commodityName;
      var final_data;
      var shortDesc;

      //console.log(paramsURL);

      function toTitleCase(str) {
        return str.replace(
          /\w\S*/g,
          function(txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
          }
        );
      }

      function groupBy(objectArray, property) {
        return objectArray.reduce(function (acc, obj) {
          var key = obj[property];
          if (!acc[key]) {
            acc[key] = [];
          }
          acc[key].push(obj);
          return acc;
        }, {});
      }

      $.ajax({
        cache: false,
        method: 'GET',
        url: paramsURL,
        // contentType: 'application/json',
        data: data,
        // dataType: 'json',
        error: function (e, textStatus, errorThrown) {
          console.log('No response');
          console.log(textStatus,errorThrown);
        },
        success: function (data) {
          var sect;
          sect = data.sector_desc;
          //console.log(data.sector_desc);
          for (var i = 0; i < sect.length; i++) {
            var sectors = sect[i];
            $('#sector').append('<option value="' + sectors + '">' + toTitleCase(sectors) + '</option>');
          }
        }
      }); 

      $('#sector').on('change', function(e) {
        sectorName = $(this).val();
        // sectorName = sectorName.replace(/\W+/g, '-');
        // sectorName = sectorName.replace(/\s+/g, '-').toLowerCase();
        window.history.replaceState(null, null, '?sector=' + encodeURIComponent(sectorName));

        $.ajax({
          cache: false,
          method: 'GET',
          url: groupsURL + '?sector=' + encodeURIComponent(sectorName),
          data: data,
          error: function (e, textStatus, errorThrown) {
            console.log('No response');
            console.log(textStatus,errorThrown);
          },
          beforeSend: function() {
            $('#group').empty();
            $('#group').append('<option>Select a group</option>');
          },
          success: function (data) {
            var group;
            group = data.group_desc;
            //console.log(data.group_desc);
            for (var i = 0; i < group.length; i++) {
              var groups = group[i];
              $('#group').append('<option value="' + groups + '">' + toTitleCase(groups) + '</option>');
            }
          }
        }); 

      });

      $('#group').on('change', function (e) {
        groupName = $(this).val();

        const url = new URL(window.location.href);
        url.searchParams.set('sector', sectorName);
        url.searchParams.set('group', groupName);
        window.history.replaceState(null, null, url);
        //(commodityURL);

        $.ajax({
          cache: false,
          method: 'GET',
          url: commodityURL + '?sector=' + encodeURIComponent(sectorName) + '&group=' + encodeURIComponent(groupName),
          data: data,
          error: function (e, textStatus, errorThrown) {
            console.log('No response');
            console.log(textStatus,errorThrown);
          },
          beforeSend: function() {
            $('#commodity').empty();
            $('#commodity').append('<option>Select a commodity</option>');
          },
          success: function (data) {
            var commodity;
            commodity = data.commodity_desc;
            //console.log(data);
            //console.log(commodity);
            //console.log(sectorName);
            //console.log(groupName);            
            for (var i = 0; i < commodity.length; i++) {
              var commodities = commodity[i];
              $('#commodity').append('<option value="' + commodities + '">' + toTitleCase(commodities) + '</option>');
            }
          
          }
        }); 

      });

      $('#commodity').on('change', function (e) {
        commodityName = $(this).val();

        const url = new URL(window.location.href);
        url.searchParams.set('sector', sectorName);
        url.searchParams.set('group', groupName);
        url.searchParams.set('commodity', commodityName);        
        window.history.replaceState(null, null, url);
        //console.log(commodityName);

        $.ajax({
          cache: false,
          method: 'GET',
          url: resultsURL + '?sector=' + encodeURIComponent(sectorName) + '&group=' + encodeURIComponent(groupName) + '&commodity=' + encodeURIComponent(commodityName),
          data: data,
          error: function (e, textStatus, errorThrown) {
            console.log('No response');
            console.log(textStatus,errorThrown);
          },
          beforeSend: function() {
            // $('#commodity').empty();
            // $('#commodity').append('<option>Select a commodity</option>');
          },
          success: function (data) {
            //var commodity;
            final_data = data;
            //console.log(final_data.short_desc);      
            for (var i = 0; i < final_data.short_desc.length; i++) {
              var checboxes = final_data.short_desc[i];
              //console.log(checboxes);
              $('#quickstat-checkboxes').append('<li class="quickstats-list-item checkboxes"><input class="usa-checkbox__input" type="checkbox" id="quick-stats-' + i + '" value="' + checboxes + '"><label class="usa-checkbox__label" for="quick-stats-' + i + '">' + checboxes + '</label></li>');
            }
          }
        }); 

      });

      $(document).on('change', '.usa-checkbox__input', function () {

        var dataQuery = [];
        var checkboxes = document.querySelectorAll('input[type=checkbox]:checked');

        for (var i = 0; i < checkboxes.length; i++) {
          dataQuery.push('short_desc=' + encodeURIComponent(checkboxes[i].value)+ '&');
        }

        shortDesc = dataQuery.join('');
        shortDesc = shortDesc.slice(0, -1);

        console.log(shortDesc);

        if (dataQuery.length > 0) {
          $('#quickstats-results').prop("disabled", false);
        } else {
          $('#quickstats-results').prop("disabled", true);
        }

      });      
      $('#quickstats-results').click(function () {
        //console.log(shortDesc);
        //console.log(shortdescURL + '?sector_desc=' + encodeURIComponent(sectorName) + '&group_desc=' + encodeURIComponent(groupName) + '&commodity_desc=' + encodeURIComponent(commodityName) + '&' + shortDesc);
        
        var newResultURL = shortdescURL + '?sector_desc=' + encodeURIComponent(sectorName) + '&group_desc=' + encodeURIComponent(groupName) + '&commodity_desc=' + encodeURIComponent(commodityName) + '&' + shortDesc + 'reference_period_desc=YEAR&agg_level_desc=NATIONAL&source_desc=SURVEY&freq_desc=ANNUAL';
        console.log(newResultURL);

        $.ajax({
          cache: false,
          method: 'GET',
          url: newResultURL,
          data: data,
          error: function (e, textStatus, errorThrown, data) {
            console.log('No response');
            console.log(textStatus,errorThrown,data);
          },
          beforeSend: function() {
          },
          success: function (data) {
            
            var UUID = data;

            $.ajax({
              cache: false,
              method: 'GET',
              url: uuiddecodeURL + '?uuid=' + UUID,
              data: data,
              error: function (e, textStatus, errorThrown, data) {
                console.log('No response');
                console.log(textStatus,errorThrown,data);
              },
              beforeSend: function() {
              },
              success: function (data) {
                //console.log(data);

                var groupedPeople = groupBy(data.items, 'year');

                console.log(groupedPeople);

              }
            }); 
          }
        }); 
      });

    }
  }
}(jQuery, Drupal, drupalSettings));