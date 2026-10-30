import { BrowserRouter, Routes, Route } from "react-router-dom";
import DefaultLayout from "./layouts/DefaultLayout";
import Login from "./pages/Login";
import { GlobalProvider } from "./contexts/GlobalContext";
import Dashboard from "./pages/Dashboard";
import Plan from "./pages/Plan";
import Timer from "./pages/Timer";
import ChatBot from "./pages/ChatBot";
import Chat from "./pages/Chat";
import NewChat from "./pages/NewChat";
import Workout from "./pages/Workout";
import Week from "./pages/Week";
import Session from "./pages/Session";
import NavigationLayout from "./layouts/NavigationLayout";

import { useEffect } from "react";
import { App as CapacitorApp } from "@capacitor/app";
import { Capacitor } from "@capacitor/core";
import { useNavigate, useLocation } from "react-router-dom";

function App() {
  function BackButtonHandler() {
    const navigate = useNavigate();
    const location = useLocation();

    useEffect(() => {
      if (!Capacitor.isNativePlatform()) return;

      const listener = CapacitorApp.addListener(
        "backButton",
        ({ canGoBack }) => {
          if (location.pathname === "/dashboard") {
            CapacitorApp.minimizeApp();
          } else if (location.pathname === "/") {
            // Non tornare indietro dalla pagina di login.
            CapacitorApp.minimizeApp();
          } else if (canGoBack) {
            navigate(-1);
          } else {
            navigate("/dashboard", { replace: true });
          }
        },
      );

      return () => {
        listener.then((handle) => handle.remove());
      };
    }, [location.pathname, navigate]);

    return null;
  }

  return (
    <>
      <BrowserRouter>
        <GlobalProvider>
          <BackButtonHandler />

          <Routes>
            <Route element={<DefaultLayout />}>
              <Route path="/" element={<Login />} />
            </Route>
            <Route element={<NavigationLayout />}>
              <Route path="/dashboard" element={<Dashboard />} />
              <Route path="/plan" element={<Plan />} />
              <Route path="/plan/:scheda/:workout" element={<Workout />} />
              <Route path="/plan/:scheda/:workout/:week" element={<Week />} />
              <Route
                path="/plan/:scheda/:workout/:week/:session"
                element={<Session />}
              />
              <Route path="/timer" element={<Timer />} />
              <Route path="/chat-bot" element={<ChatBot />} />
              <Route path="/chat-bot/:chat" element={<Chat />} />
              <Route path="/chat-bot/new-chat" element={<NewChat />} />
            </Route>
          </Routes>
        </GlobalProvider>
      </BrowserRouter>
    </>
  );
}

export default App;
