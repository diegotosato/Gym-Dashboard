import axios from "axios";
import { createContext, useContext, useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";

const GlobalContext = createContext();

function GlobalProvider({ children }) {
  const [user, setUser] = useState(null);
  const [isLoadingUser, setIsLoadingUser] = useState(true);

  const [expired, setExpired] = useState(false);

  const navigate = useNavigate();

  useEffect(() => {
    async function restoreUser() {
      try {
        await axios.get("/api/user").then((res) => {
          setUser(res.data.user);
        });
      } catch (err) {
        if (err.status > 400 || err.status < 500) {
          setUser(null);
          localStorage.removeItem("auth_token");
          delete axios.defaults.headers.common["Authorization"];
          setExpired(true);
          navigate("/");
        }
      } finally {
        setIsLoadingUser(false);
      }
    }

    restoreUser();
  }, []);

  const values = {
    user,
    setUser,
    isLoadingUser,
    setIsLoadingUser,
    expired,
    setExpired,
  };

  return (
    <GlobalContext.Provider value={values}>{children}</GlobalContext.Provider>
  );
}

function useGlobalContext() {
  return useContext(GlobalContext);
}

export { GlobalProvider, useGlobalContext };
