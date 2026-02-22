import { Outlet } from "react-router-dom";
import NavBar from "../components/NavBar";
import "./AppLayout.css";

function AppLayout() {
  return (
    <>
      <NavBar />

      <main>
        <Outlet />
      </main>
    </>
  );
}

export default AppLayout;
