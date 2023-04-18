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
      var sectorName;
      var groupName;
      var commodityName;

      //console.log(paramsURL);

      function toTitleCase(str) {
        return str.replace(
          /\w\S*/g,
          function(txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
          }
        );
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
        console.log(commodityName);

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
            console.log(final_data);
            console.log(commodityName);
            console.log(sectorName);
            console.log(groupName);            
            // for (var i = 0; i < commodity.length; i++) {
            //   var commodities = commodity[i];
            //   console.log(final_data[i]);
            // }
          
          }
        }); 

      });

    }
  }
}(jQuery, Drupal, drupalSettings));