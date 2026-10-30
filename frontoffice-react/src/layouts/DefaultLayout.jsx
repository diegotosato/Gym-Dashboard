import { Outlet } from "react-router-dom";
import AnimatedOutlet from "../components/AnimatedOutlet";

export default function DefaultLayout() {
  return (
    <main>
      <Outlet />
      {/* <AnimatedOutlet /> */}
    </main>
  );
}
