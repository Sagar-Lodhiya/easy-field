const ORS_API_KEY = "5b3ce3597851110001cf62487861d48b92b945a79c4f47036eae0e4d"; // Replace with your OpenRouteService key
const MAX_POINTS_PER_REQUEST = 49;

const map = L.map("map").setView([21.125681, 82.794998], 12);
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(map);

// Converts lat/lng -> lng/lat for ORS
const toORSFormat = (points) => points.map((p) => [p.lng, p.lat]);

// Utility: chunk large array into overlapping pieces
const chunkArray = (arr, size) => {
  const chunks = [];
  for (let i = 0; i < arr.length - 1; i += (size - 1)) {
    chunks.push(arr.slice(i, i + size));
  }
  return chunks;
};

// ORS Route Request
const fetchORSRoute = async (chunk) => {
  try {
    const response = await fetch(
      "https://api.openrouteservice.org/v2/directions/driving-car/geojson",
      {
        method: "POST",
        headers: {
          Authorization: ORS_API_KEY,
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ coordinates: chunk, instructions: false }),
      }
    );

    if (!response.ok) {
      console.error("ORS route failed:", await response.text());
      return null;
    }

    return await response.json();
  } catch (err) {
    console.error("ORS error:", err);
    return null;
  }
};

// Main drawing function
const drawFullRoute = async (points) => {
  if (points.length < 2) return;

  const coords = toORSFormat(points);
  const chunks = chunkArray(coords, MAX_POINTS_PER_REQUEST);

  for (let chunk of chunks) {
    const routeGeoJson = await fetchORSRoute(chunk);
    if (routeGeoJson) {
      L.geoJSON(routeGeoJson, {
        style: { color: "#007bff", weight: 4 },
      }).addTo(map);
    }
    await new Promise((res) => setTimeout(res, 1000)); // Delay to avoid rate limits
  }

  // Optional markers
  L.marker([points[0].lat, points[0].lng]).addTo(map).bindPopup("Start");
  L.marker([points[points.length - 1].lat, points[points.length - 1].lng])
    .addTo(map)
    .bindPopup("End");
};

// AJAX to fetch your pins
const getPins = (id) => {
  fetch(`${baseUrl}attendance/get-attendance-pins?id=${id}`)
    .then((res) => res.json())
    .then((data) => {
      if (data.length > 0) {
        drawFullRoute(data);
      }
    })
    .catch((err) => console.error("Error fetching pins:", err));
};