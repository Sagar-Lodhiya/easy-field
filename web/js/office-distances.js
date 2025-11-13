// Office form location search functionality
// This example adds a search box to a map, using the Google Place Autocomplete
// feature. People can enter geographical searches. The search box will return a
// pick list containing a mix of places and predicted search terms.
// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
function initAutocomplete() {
    console.log("initAutocomplete called for office form");
    
    // Start with a default center (but no marker or pre-filled data)
    const defaultCenter = { lat: 23.2156354, lng: 72.63694149999999 };

    const map = new google.maps.Map(document.getElementById("map"), {
      center: defaultCenter,
      zoom: 13,
      mapTypeId: "roadmap",
    });
    // Create the search box and link it to the UI element.
    const input = document.getElementById("location-search-input");
    const searchBox = new google.maps.places.SearchBox(input);
  
    // Don't move the input to map controls - keep it in its original position
    // map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    // Bias the SearchBox results towards current map's viewport.
    map.addListener("bounds_changed", () => {
      searchBox.setBounds(map.getBounds());
    });

    // Add click listener to map for manual location selection
    map.addListener("click", (event) => {
      const lat = event.latLng.lat();
      const lng = event.latLng.lng();
      
      // Clear existing markers
      markers.forEach((marker) => {
        marker.setMap(null);
      });
      markers = [];
      
      // Create new marker at clicked location
      const newMarker = new google.maps.Marker({
        map,
        position: event.latLng,
        animation: google.maps.Animation.DROP
      });
      markers.push(newMarker);
      
      // Reverse geocode to get address
      const geocoder = new google.maps.Geocoder();
      geocoder.geocode({ location: event.latLng }, (results, status) => {
        if (status === 'OK' && results[0]) {
          const place = results[0];
          const formattedAddress = place.formatted_address;
          
          // Update only latitude and longitude fields (not name)
          const latField = document.getElementById('office-latitude');
          const lngField = document.getElementById('office-longitude');
          
          if (latField) latField.value = lat;
          if (lngField) lngField.value = lng;
          
          // Update display
          const addressDisplay = document.getElementById('selected-address');
          const coordsDisplay = document.getElementById('selected-coords');
          const locationInfo = document.getElementById('location-info');
          
          if (addressDisplay) addressDisplay.textContent = formattedAddress;
          if (coordsDisplay) coordsDisplay.textContent = lat + ', ' + lng;
          if (locationInfo) locationInfo.style.display = 'block';
          
          console.log('Location selected by click:', formattedAddress, lat, lng);
        }
      });
    });
  
    let markers = [];

    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener("places_changed", () => {
      const places = searchBox.getPlaces();
  
      if (places.length == 0) {
        return;
      }
  
      // Clear out the old markers.
      markers.forEach((marker) => {
        marker.setMap(null);
      });
      markers = [];
  
      // For each place, get the icon, name and location.
      const bounds = new google.maps.LatLngBounds();
  
      places.forEach((place) => {
        if (!place.geometry || !place.geometry.location) {
          console.log("Returned place contains no geometry");
          return;
        }

        // Extract lat/lng and formatted address
        const lat = place.geometry.location.lat();
        const lng = place.geometry.location.lng();
        const formattedAddress = place.formatted_address || place.name;
        
        // Update only latitude and longitude fields (not name)
        const latField = document.getElementById('office-latitude');
        if (latField) {
          latField.value = lat;
        }
        
        const lngField = document.getElementById('office-longitude');
        if (lngField) {
          lngField.value = lng;
        }

        // Update display info
        const addressDisplay = document.getElementById('selected-address');
        if (addressDisplay) {
          addressDisplay.textContent = formattedAddress;
        }
        const coordsDisplay = document.getElementById('selected-coords');
        if (coordsDisplay) {
          coordsDisplay.textContent = lat + ', ' + lng;
        }
        const locationInfo = document.getElementById('location-info');
        if (locationInfo) {
          locationInfo.style.display = 'block';
        }

        const icon = {
          url: place.icon,
          size: new google.maps.Size(71, 71),
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(17, 34),
          scaledSize: new google.maps.Size(25, 25),
        };
  
        // Create a marker for each place.
        markers.push(
          new google.maps.Marker({
            map,
            icon,
            title: place.name,
            position: place.geometry.location,
          }),
        );
        if (place.geometry.viewport) {
          // Only geocodes have viewport.
          bounds.union(place.geometry.viewport);
        } else {
          bounds.extend(place.geometry.location);
        }
      });
      map.fitBounds(bounds);
    });
  }
  
  window.initAutocomplete = initAutocomplete;
