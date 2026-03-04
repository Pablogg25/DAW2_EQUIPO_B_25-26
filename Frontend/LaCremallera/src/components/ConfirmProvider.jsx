import { useState } from "react";
import { ConfirmContext } from "./ConfirmContext";

export function ConfirmProvider({ children }) {
  const [confirmData, setConfirmData] = useState(null);

  function confirm(message) {
    return new Promise((resolve) => {
      setConfirmData({ message, resolve });
    });
  }

  function aceptar() {
    confirmData.resolve(true);
    setConfirmData(null);
  }

  function cancelar() {
    confirmData.resolve(false);
    setConfirmData(null);
  }

  return (
    <ConfirmContext.Provider value={{ confirm }}>
      {children}

      {confirmData && (
        <div style={styles.overlay}>
          <div style={styles.modal}>
            <p className="mb-3">{confirmData.message}</p>

            <div style={styles.buttons}>
              <button className="btn btn-danger" onClick={aceptar}>
                Sí
              </button>
              <button className="btn btn-secondary" onClick={cancelar}>
                No
              </button>
            </div>
          </div>
        </div>
      )}
    </ConfirmContext.Provider>
  );
}

const styles = {
  overlay: {
    position: "fixed",
    top: 0,
    left: 0,
    width: "100vw",
    height: "100vh",
    background: "rgba(0,0,0,0.5)",
    display: "flex",
    justifyContent: "center",
    alignItems: "center",
    zIndex: 9999,
  },
  modal: {
    background: "white",
    padding: "20px",
    borderRadius: "8px",
    minWidth: "300px",
    textAlign: "center",
    boxShadow: "0 4px 12px rgba(0,0,0,0.2)",
  },
  buttons: {
    display: "flex",
    justifyContent: "space-around",
    marginTop: "15px",
  },
};
