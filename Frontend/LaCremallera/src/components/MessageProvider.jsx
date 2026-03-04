import { useState } from "react";
import { MessageContext } from "./MessageContext";

export function MessageProvider({ children }) {
  const [messages, setMessages] = useState([]);

  function showMessage(text, type = "info", duration = 3000) {
    const id = Date.now();

    setMessages((prev) => [...prev, { id, text, type }]);

    setTimeout(() => {
      setMessages((prev) => prev.filter((m) => m.id !== id));
    }, duration);
  }

  return (
    <MessageContext.Provider value={{ showMessage }}>
      {children}

      <div style={styles.wrapper}>
        {messages.map((m) => (
          <div key={m.id} style={{ ...styles.toast, ...styles[m.type] }}>
            {m.text}
          </div>
        ))}
      </div>
    </MessageContext.Provider>
  );
}

const styles = {
  wrapper: {
    position: "fixed",
    bottom: "30px",
    left: "50%",
    transform: "translateX(-50%)",
    zIndex: 9999,
    pointerEvents: "none",
  },
  container: {
    display: "flex",
    flexDirection: "column",
    alignItems: "center",
    gap: "10px",
  },
  toast: {
    padding: "12px 18px",
    borderRadius: "6px",
    color: "white",
    fontWeight: "bold",
    boxShadow: "0 2px 6px rgba(0,0,0,0.2)",
    pointerEvents: "auto",
    animation: "fadeInUp 0.35s ease-out",
  },
  info: { background: "#3498db" },
  success: { background: "#2ecc71" },
  warning: { background: "#f1c40f", color: "#ffffff" },
  error: { background: "#e74c3c" },
};
