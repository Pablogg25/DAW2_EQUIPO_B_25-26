import { StrictMode } from "react";
import { createRoot } from "react-dom/client";

import AppEnrutador from "./router/AppEnrutador";
// import { SeguridadProvider } from "./context/SeguridadProvider.jsx";

createRoot(document.getElementById("root")).render(
  <StrictMode>
    {/* <SeguridadProvider> */}
    <AppEnrutador />
    {/* </SeguridadProvider> */}
  </StrictMode>,
);
