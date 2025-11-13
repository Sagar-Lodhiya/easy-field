const initMap = async (routePoints) => {
  const { Map } = await google.maps.importLibrary("maps");
  const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

  const center = { lat: 21.125681, lng: 82.794998 };
  const map = new Map(document.getElementById("map"), {
    zoom: 11,
    center,
    mapId: "TFfeeBO7k|tcHB-=123",
  });

  const directionsService = new google.maps.DirectionsService();
  const directionsRenderer = new google.maps.DirectionsRenderer({
    map: map,
    suppressMarkers: true,
  });

  if (routePoints.length < 2) return;

  const origin = routePoints[0];
  const destination = routePoints[routePoints.length - 1];

  // ✅ Limit waypoints to avoid exceeding Google Maps API limits
  let waypoints = routePoints.slice(1, -1); // Exclude first and last points
  const MAX_WAYPOINTS = 9; // Limit waypoints (API free limit: 10 total including start & end)

  if (waypoints.length > MAX_WAYPOINTS) {
    const step = Math.ceil(waypoints.length / MAX_WAYPOINTS);
    waypoints = waypoints.filter((_, index) => index % step === 0);
  }

  waypoints = waypoints.map((point) => ({
    location: point,
    stopover: true,
  }));

  directionsService.route(
    {
      origin,
      destination,
      waypoints,
      travelMode: google.maps.TravelMode.DRIVING,
      // optimizeWaypoints: true, // ✅ Reorders for best efficiency
    },
    (result, status) => {
      if (status === google.maps.DirectionsStatus.OK) {
        directionsRenderer.setDirections(result);
      } else {
        console.error("Directions request failed:", status);
      }
    }
  );

  new google.maps.marker.AdvancedMarkerElement({
    position: origin,
    map: map,
    title: "Start Point",
  });

  new google.maps.marker.AdvancedMarkerElement({
    position: destination,
    map: map,
    title: "End Point",
  });
  
  routePoints.slice(1, -1).map(item => {
    new google.maps.marker.AdvancedMarkerElement({
      position: item,
      map: map,
      title: "Track Point",
    });
    
  })
};


const getPins = (id) => {
  $.ajax({
    url: `${baseUrl}attendance/get-attendance-pins?id=${id}`,
    method: "GET",
    success: (response) => {
      const data = JSON.parse(response);
      if (data.length > 0) {
        initMap(data);
      }
    },
  });
};
