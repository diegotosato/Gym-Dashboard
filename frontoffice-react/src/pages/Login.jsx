import axios from "axios";
import { useState } from "react";
import { useGlobalContext } from "../contexts/GlobalContext";
import { Tailspin } from "ldrs/react";
import "ldrs/react/Tailspin.css";
import { useNavigate } from "react-router-dom";

export default function Login() {
  const { setUser, expired } = useGlobalContext();

  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");

  const [invalid, setInvalid] = useState(false);
  const [loader, setLoader] = useState(false);

  let navigate = useNavigate();

  async function submitLogin(e) {
    e.preventDefault();

    const formData = {
      username: username,
      password: password,
    };

    if (formData.username === "" || formData.password === "") {
      setInvalid(true);
      return;
    }

    try {
      setLoader(true);

      await axios.post("/api/client-login", formData).then((response) => {
        const res = response.data;
        localStorage.setItem("auth_token", res.token);
        axios.defaults.headers.common["Authorization"] = `Bearer ${res.token}`;
        setUser(res.user);
        navigate("/dashboard");
      });
    } catch (err) {
      console.log("Errore login:", err);
      console.log("Risposta server:", err.response?.status, err.response?.data);
      alert(
        err.response
          ? `Errore ${err.response.status}: ${JSON.stringify(
              err.response.data,
            )}`
          : `Errore di connessione: ${err.message}`,
      );
    } finally {
      setLoader(false);
    }
  }

  return (
    <div className="login-page">
      <div className="login-background" aria-hidden="true" />

      <div className="login-header">
        <div className="logo">
          <img src="/logo_colori.png" alt="logo_visual" />

          <div className="logo-texts">
            <h2>GYMManager</h2>
            <p>La tua palestra, sempre con te</p>
          </div>
        </div>
        <div className="welcome-message">
          <h2>Bentornato!</h2>
          <p>
            Accedi al tuo account per continuare il tuo percorso di allenamento
          </p>
        </div>
        <div className="login-form">
          <form method="post" id="login-form" onSubmit={(e) => submitLogin(e)}>
            <div className="input-div">
              <i className="fa-solid fa-user"></i>
              <input
                className={username === "" && invalid ? "invalid-input" : ""}
                type="text"
                name="username"
                id="username"
                autoComplete="current-username"
                value={username}
                onChange={(e) => setUsername(e.target.value)}
              />
              {username === "" && invalid && (
                <i className="fa-solid fa-circle-exclamation"></i>
              )}
            </div>

            <div className="input-div">
              <i className="fa-solid fa-lock"></i>
              <input
                className={password === "" && invalid ? "invalid-input" : ""}
                type="password"
                name="password"
                id="password"
                autoComplete="current-password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
              />
              {password === "" && invalid && (
                <i className="fa-solid fa-circle-exclamation"></i>
              )}
            </div>

            <button type="submit">accedi</button>
          </form>
        </div>
      </div>

      {expired && (
        <small className="expired-message">
          <i className="fa-solid fa-circle-exclamation"></i> Sessione scaduta
        </small>
      )}

      {loader && (
        <div className="loader">
          <Tailspin size="30" stroke="5" speed="1.5" color="#4f46e5" />
        </div>
      )}
    </div>
  );
}
