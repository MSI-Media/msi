
    <script src="http://www.google.com/jsapi" type="text/javascript"></script>
    <script type="text/javascript">
      google.load('search', '1');

      /**
       * Extracts the users query from the URL.
       */ 
      function getQuery() {
        var url = '' + window.location;
        var queryStart = url.indexOf('?') + 1;
        if (queryStart > 0) {
          var parts = url.substr(queryStart).split('&');
          for (var i = 0; i < parts.length; i++) {
            if (parts[i].length > 2 && parts[i].substr(0, 2) == 'q=') {
              return decodeURIComponent(
                  parts[i].split('=')[1].replace(/\+/g, ' '));
            }
          }
        }
        return '';
      }

      function onLoad() {
        // Create a custom search control that uses a CSE restricted to
        // code.google.com


        var customSearchControl = new google.search.CustomSearchControl(
            '006090730058121165205:lyncqx30fi4');
	var drawOptions = new google.search.DrawOptions();
        drawOptions.enableSearchResultsOnly();
        drawOptions.setAutoComplete(true);
	customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);	
	customSearchControl.setLinkTarget(google.search.Search.LINK_TARGET_SELF);
        // Draw the control in content div
        customSearchControl.draw('results', drawOptions);
        //customSearchControl.draw('cse', drawOptions);
        // Run a query
        customSearchControl.execute(getQuery());
      }

      google.setOnLoadCallback(onLoad);
    </script>
  </head>
    <div class=ptextleft id="results">Loading...</div>