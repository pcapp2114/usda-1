(function ($, Drupal, drupalSettings) {
  //console.log(drupalSettings);
  Drupal.behaviors.quickStats = {
    attach: function (context, settings) {

      var data;
      var UUID;
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

      // Return item in Title case
      function toTitleCase(str) {
        return str.replace(
          /\w\S*/g,
          function(txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
          }
        );
      }

      // Group array by property
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

      // Get URL parameter
      var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;
    
        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');
    
            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
        return false;
      };

      // accending sort
      function asc_sort(a, b){
        return ($(b).text()) < ($(a).text()) ? 1 : -1;    
      }

      if (getUrlParameter('UUID') || getUrlParameter('sector') && getUrlParameter('group') && getUrlParameter('commodity') && getUrlParameter('UUID')) {
       
        UUID = getUrlParameter('UUID');

        $('#ajaxLoader').toggle();

        $.ajax({
          cache: false,
          method: 'GET',
          url: uuiddecodeURL + '?uuid=' + UUID,
          data: data,
          error: function (e, textStatus, errorThrown, data) {
            console.log('No response');
            console.log(textStatus, errorThrown, data);
          },
          success: function (data) {

            $('#ajaxLoader').toggle();
            //$('#quickstats').hide();

            //console.log(data); //<-- Keep this for debugging
            $('#quickstats-results').prop("disabled", true);

            var groupedYears = groupBy(data.items, 'year');
            var groupedDesc = groupBy(data.items.reverse(), 'short_desc');

            var years = [];

            if (data.numRows == 0) {
              $('#results-table').append('<h3>No Results</h3><br>');
            } else {

              // Table header
              $.each(groupedYears, function (key, value) {
                $('#results-table table thead tr').prepend('<th>' + key + '</th>');
                // Gather all of the years into an array
                years.push(key);
                years = years.sort().reverse();
              });

              // Table title
              $('#results-table table thead tr').prepend('<th>Data Items</th>');

              //console.log(groupedDesc);

              var html = '';
              // Go through each item grouped description
              $.each(groupedDesc, function (key, value) {

                // Append the year to the top row
                $('#results-table table tbody').append('<tr class="row" data-sort="' + key + '"><td>' + key + '</td></tr>');

                // // Go through each year and create the table with data attributes
                for (var i = 0; i < years.length; i++) {
                  $('#results-table table tbody').find("[data-sort='" + key + "']").append('<td data-result="' + key + '" data-year="' + years[i] + '"></td>');
                }

                // Go through each data set and plug it into the table
                $.each(value, function (ky, val) {
                  $('#results-table table td').filter('[data-year="' + val.year + '"]').filter('[data-result="' + val.short_desc + '"]').append(val.published_estimate);
                });

              });
            
              // Reorder rows in alpha order
              var $tbody = $('#results-table table tbody');
              $tbody.find('tr').sort(function (a, b) {
                var tda = $(a).attr('data-sort');
                var tdb = $(b).attr('data-sort');
                return tda > tdb ? 1
                  : tda < tdb ? -1
                    : 0;
              }).appendTo($tbody);
            }
          }
        });        

      } else {

        $.ajax({
          cache: false,
          method: 'GET',
          url: paramsURL,
          // contentType: 'application/json',
          data: data,
          // dataType: 'json',
          error: function (e, textStatus, errorThrown) {
            console.log('No response');
            console.log(textStatus, errorThrown);
          },
          beforeSend: function () {
            $('#sector-ajaxLoader').toggle();
          },
          success: function (data) {
            $('#sector-ajaxLoader').toggle();
            $('#sector-wrap').animate({ opacity: 1 });

            var sect;
            sect = data.sector_desc;

            for (var i = 0; i < sect.length; i++) {
              var sectors = sect[i];
              $('#sector').append('<option value="' + sectors + '">' + toTitleCase(sectors) + '</option>');
            }

            // if (getUrlParameter('sector')) {
            //   console.log(getUrlParameter('sector'));
            //   $('#sector').val(getUrlParameter('sector')); 
            //   $('#sector').change();
            // }

          }
        });

        $('#sector').on('change', function (e) {
          sectorName = $(this).val();
          //window.history.replaceState(null, null, '?sector=' + encodeURIComponent(sectorName));

          // if (getUrlParameter('sector') && !getUrlParameter('group') && !getUrlParameter('commodity')) {
          //   const url = new URL(window.location.href);
          //   url.searchParams.set('sector', sectorName);
          //   window.history.replaceState(null, null, url); 
          // }

          $('#quickstat-checkboxes').empty();
          $('#results-table td').empty();
          $.ajax({
            cache: false,
            method: 'GET',
            url: groupsURL + '?sector=' + encodeURIComponent(sectorName),
            data: data,
            error: function (e, textStatus, errorThrown) {
              console.log('No response');
              console.log(textStatus, errorThrown);
            },
            beforeSend: function () {
              $('#group-ajaxLoader').toggle();
              $('#group').empty();
              $('#group').append('<option>Select a group</option>');
            },
            success: function (data) {
              $('#group-ajaxLoader').toggle();
              var group;
              group = data.group_desc;
              for (var i = 0; i < group.length; i++) {
                var groups = group[i];
                $('#group').append('<option value="' + groups + '">' + toTitleCase(groups) + '</option>');
              }
              $('#group-wrap').animate({ opacity: 1 });

              // if (getUrlParameter('group')) {
              //   console.log(getUrlParameter('group'));
              //   $('#group').val(getUrlParameter('group')); 
              //   $('#group').change();
              // }

            }
          });

        });

        $('#group').on('change', function (e) {
          groupName = $(this).val();

          //if (getUrlParameter('sector') && getUrlParameter('group') && !getUrlParameter('commodity')) {
          // const url = new URL(window.location.href);
          // url.searchParams.set('sector', sectorName);
          // url.searchParams.set('group', groupName);
          // window.history.replaceState(null, null, url);
          //}

          $('#quickstat-checkboxes').empty();
          $('#results-table td').empty();
          $.ajax({
            cache: false,
            method: 'GET',
            url: commodityURL + '?sector=' + encodeURIComponent(sectorName) + '&group=' + encodeURIComponent(groupName),
            data: data,
            error: function (e, textStatus, errorThrown) {
              console.log('No response');
              console.log(textStatus, errorThrown);
            },
            beforeSend: function () {
              $('#commodity-ajaxLoader').toggle();
              $('#commodity').empty();
              $('#commodity').append('<option>Select a commodity</option>');
            },
            success: function (data) {
              $('#commodity-ajaxLoader').toggle();
              var commodity;
              commodity = data.commodity_desc;
              for (var i = 0; i < commodity.length; i++) {
                var commodities = commodity[i];
                $('#commodity').append('<option value="' + commodities + '">' + toTitleCase(commodities) + '</option>');
              }
              $('#commodity-wrap').animate({ opacity: 1 });

              // if (getUrlParameter('commodity')) {
              //   console.log(getUrlParameter('commodity'));
              //   $('#commodity').val(getUrlParameter('commodity')); 
              //   $('#commodity').change();
              // }

            }
          });

        });

        $('#commodity').on('change', function (e) {
          commodityName = $(this).val();

          //if (getUrlParameter('sector') && getUrlParameter('group') && getUrlParameter('commodity')) {
          // const url = new URL(window.location.href);
          // url.searchParams.set('sector', sectorName);
          // url.searchParams.set('group', groupName);      
          // window.history.replaceState(null, null, url);
          //}

          $('#quickstat-checkboxes').empty();
          $('#results-table td').empty();
          $.ajax({
            cache: false,
            method: 'GET',
            url: resultsURL + '?sector=' + encodeURIComponent(sectorName) + '&group=' + encodeURIComponent(groupName) + '&commodity=' + encodeURIComponent(commodityName),
            data: data,
            error: function (e, textStatus, errorThrown) {
              console.log('No response');
              console.log(textStatus, errorThrown);
            },
            beforeSend: function () {

            },
            success: function (data) {
              final_data = data;
              if (final_data.short_desc.length == 0) {
                $('#quickstat-checkboxes').append('<h3>No Results</h3><br>');
              } else {
                for (var i = 0; i < final_data.short_desc.length; i++) {
                  var checboxes = final_data.short_desc[i];
                  $('#quickstat-checkboxes').append('<li class="quickstats-list-item checkboxes"><input class="usa-checkbox__input" type="checkbox" id="quick-stats-' + i + '" value="' + checboxes + '"><label class="usa-checkbox__label" for="quick-stats-' + i + '">' + checboxes + '</label></li>');
                }
              }
            }
          });

        });

        $(document).on('change', '.usa-checkbox__input', function () {

          var dataQuery = [];
          var checkboxes = document.querySelectorAll('input[type=checkbox]:checked');

          for (var i = 0; i < checkboxes.length; i++) {
            dataQuery.push('short_desc=' + encodeURIComponent(checkboxes[i].value) + '&');
          }

          shortDesc = dataQuery.join('');
          shortDesc = shortDesc.slice(0, -1);

          if (dataQuery.length > 0) {
            $('#quickstats-results').prop("disabled", false);
          } else {
            $('#quickstats-results').prop("disabled", true);
          }

          $('#results-table tbody, #results-table thead').empty();

        });
        
        $('#quickstats-results').on('click', function () {
          var newResultURL = shortdescURL + '?sector_desc=' + encodeURIComponent(sectorName) + '&group_desc=' + encodeURIComponent(groupName) + '&commodity_desc=' + encodeURIComponent(commodityName) + '&' + shortDesc + '&reference_period_desc=YEAR&agg_level_desc=NATIONAL&source_desc=SURVEY&freq_desc=ANNUAL';
          
          $.ajax({
            cache: false,
            method: 'GET',
            url: newResultURL,
            data: data,
            error: function (e, textStatus, errorThrown, data) {
              console.log('No response');
              console.log(textStatus, errorThrown, data);
            },
            beforeSend: function () {
              $('#ajaxLoader').toggle();
            },
            success: function (data) {

              if (getUrlParameter('UUID')) {
                UUID = getUrlParameter('UUID');
              } else {
                UUID = data;
              }
                  
              $.ajax({
                cache: false,
                method: 'GET',
                url: uuiddecodeURL + '?uuid=' + UUID,
                data: data,
                error: function (e, textStatus, errorThrown, data) {
                  console.log('No response');
                  console.log(textStatus, errorThrown, data);
                },
                success: function (data) {

                  const url = new URL(window.location.href);
                  url.searchParams.set('sector', sectorName);
                  url.searchParams.set('group', groupName);
                  url.searchParams.set('commodity', commodityName);
                  url.searchParams.set('UUID', UUID);
                  window.history.replaceState(null, null, url);

                  $('#ajaxLoader').toggle();

                  //console.log(data); //<-- Keep this for debugging
                  $('#quickstats-results').prop("disabled", true);

                  var groupedYears = groupBy(data.items, 'year');
                  var groupedDesc = groupBy(data.items.reverse(), 'short_desc');

                  var years = [];

                  if (data.numRows == 0) {
                    $('#results-table').append('<h3>No Results</h3><br>');
                  } else {

                    $('#results-table table thead').append('<tr></tr>');

                    // Table header
                    $.each(groupedYears, function (key, value) {
                      $('#results-table table thead tr').prepend('<th>' + key + '</th>');
                      // Gather all of the years into an array
                      years.push(key);
                      years = years.sort().reverse();
                    });

                    // Table title
                    $('#results-table table thead tr').prepend('<th>Data Items</th>');

                    //console.log(groupedDesc);

                    var html = '';
                    // Go through each item grouped description
                    $.each(groupedDesc, function (key, value) {

                      // Append the year to the top row
                      $('#results-table table tbody').append('<tr class="row" data-sort="' + key + '"><td>' + key + '</td></tr>');

                      // // Go through each year and create the table with data attributes
                      for (var i = 0; i < years.length; i++) {
                        $('#results-table table tbody').find("[data-sort='" + key + "']").append('<td data-result="' + key + '" data-year="' + years[i] + '"></td>');
                      }

                      // Go through each data set and plug it into the table
                      $.each(value, function (ky, val) {
                        $('#results-table table td').filter('[data-year="' + val.year + '"]').filter('[data-result="' + val.short_desc + '"]').append(val.published_estimate);
                      });

                    });
                    
                    // Reorder rows in alpha order
                    var $tbody = $('#results-table table tbody');
                    $tbody.find('tr').sort(function(a, b) {
                      var tda = $(a).attr('data-sort');
                      var tdb = $(b).attr('data-sort');
                      return tda > tdb ? 1
                        : tda < tdb ? -1
                        : 0;
                    }).appendTo($tbody);

                  }
                }
              });
            }
          });
        });
      }
    }
  }
}(jQuery, Drupal, drupalSettings));