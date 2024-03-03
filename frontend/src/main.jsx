import React from "react";
import ReactDOM from "react-dom/client";
import App from "./App.jsx";
import AppProviders from "./core/AppProviders/AppProviders.jsx";
import "./index.scss";

ReactDOM.createRoot(document.getElementById("root")).render(
  <React.StrictMode>
    <AppProviders>
      <App />
    </AppProviders>
  </React.StrictMode>
);