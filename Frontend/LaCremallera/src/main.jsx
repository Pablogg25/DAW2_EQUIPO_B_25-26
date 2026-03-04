import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import "bootstrap/dist/css/bootstrap.min.css";

import AppEnrutador from "./router/AppEnrutador";
import AuthProvider from "./context/AuthProvider";

import { MessageProvider } from "./components/MessageProvider";
import { ConfirmProvider } from "./components/ConfirmProvider";

createRoot(document.getElementById("root")).render(
  <StrictMode>
    <AuthProvider>
      <MessageProvider>
        <ConfirmProvider>
          <AppEnrutador />
        </ConfirmProvider>
      </MessageProvider>
    </AuthProvider>
  </StrictMode>,
);
