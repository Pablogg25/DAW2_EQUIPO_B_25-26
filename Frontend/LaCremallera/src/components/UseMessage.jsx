import { useContext } from "react";
import { MessageContext } from "./MessageContext";

export function useMessage() {
  return useContext(MessageContext);
}
