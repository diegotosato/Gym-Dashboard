import { Outlet } from "react-router-dom";
import Navigation from "../components/Navigation";
import AnimatedOutlet from "../components/AnimatedOutlet";

export default function NavigationLayout() {
  return (
    <main className="app-shell">
      <Navigation />
      {/* <Outlet /> */}
      <AnimatedOutlet />
    </main>
  );
}
