import { createRoot } from "react-dom/client";
import "./index.css";
import App from "./App.jsx";
import axios from "axios";

// impostazioni default chiamate axios
axios.defaults.baseURL = "http://localhost:8000";
// axios.defaults.baseURL = "https://species-erp-sink-ensuring.trycloudflare.com";
axios.defaults.headers.common["Accept"] = "application/json";

const savedToken = localStorage.getItem("auth_token");
if (savedToken) {
  axios.defaults.headers.common["Authorization"] = `Bearer ${savedToken}`;
}

createRoot(document.getElementById("root")).render(<App />);
