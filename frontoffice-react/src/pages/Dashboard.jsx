import axios from "axios";
import { useGlobalContext } from "../contexts/GlobalContext";
import { Tailspin } from "ldrs/react";
import "ldrs/react/Tailspin.css";
import { useNavigate } from "react-router-dom";
import { createPortal } from "react-dom";
import { useState } from "react";

import { motion, AnimatePresence } from "motion/react";

export default function Dashboard() {
  const { user, isLoadingUser } = useGlobalContext();

  const navigate = useNavigate();

  const [openPortal, setOpenPortal] = useState(false);

  async function logout(e) {
    e.preventDefault();

    try {
      await axios.post("/api/logout", user);
    } catch (err) {
      console.log(err);
    } finally {
      navigate("/");
    }
  }

  return (
    <>
      <div className="dashboard-header">
        <h2 className="section-title">Profilo</h2>
        <button onClick={() => setOpenPortal(true)} className="logout-btn">
          <i className="fa-solid fa-right-from-bracket"></i> Logout
        </button>
      </div>

      {!isLoadingUser ? (
        <>
          <div className="info-box">
            <div className="user-avatar">
              {user.client.first_name[0]}
              {user.client.last_name[0]}
            </div>

            <div className="user-info">
              <p className="name">
                {user.client.first_name} {user.client.last_name}
              </p>

              <p className="email">{user.client.email}</p>
            </div>
          </div>

          <div className="data-box">
            <p className="box-title">
              <i className="fa-solid fa-user"></i> Dati anagrafici
            </p>

            <table className="box-table">
              <tbody>
                <tr>
                  <td>Data di nascita</td>
                  <td>{user.client.date_of_birth}</td>
                </tr>
                <tr>
                  <td>Sesso</td>
                  <td>
                    {user.client.gender === "male" ? "Maschio" : "Femmina"}
                  </td>
                </tr>
                <tr>
                  <td>Telefono</td>
                  <td>{user.client.phone}</td>
                </tr>
                <tr>
                  <td>Indirizzo</td>
                  <td>{user.client.address}</td>
                </tr>
                <tr>
                  <td>Codice fiscale</td>
                  <td>{user.client.tax_code}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div className="data-box">
            <p className="box-title">
              <i className="fa-regular fa-id-card"></i> Abbonamento
            </p>

            <table className="box-table">
              <tbody>
                <tr>
                  <td>Dal</td>
                  <td>{user.sub.start_date}</td>
                </tr>
                <tr>
                  <td>Al</td>
                  <td>{user.sub.base_end_date}</td>
                </tr>
                <tr>
                  <td>Tipologia</td>
                  <td>
                    {user.sub.type === "annual"
                      ? "Annuale"
                      : user.sub.type === "monthly"
                        ? "Mensile"
                        : "4 mesi"}
                  </td>
                </tr>
              </tbody>
            </table>

            {user.sub_status === "active" ? (
              <small className="active-tag">Attivo</small>
            ) : user.sub_status === "expired" ? (
              <small className="danger-tag">Scaduto</small>
            ) : user.sub_status === "suspended" ? (
              <small className="warning-tag">Sospeso</small>
            ) : (
              <small className="info-tag">In attesa</small>
            )}
          </div>

          <div className="data-box">
            <p className="box-title">
              <i className="fa-solid fa-shield-halved"></i> Certificato Medico
            </p>

            <table className="box-table">
              <tbody>
                <tr>
                  {user.medical_certificate_status === "active" ? (
                    <td>Scade il</td>
                  ) : (
                    <td>Scaduto il</td>
                  )}
                  <td>
                    {user.medical_certificate.medical_certificate_expiry_date}
                  </td>
                </tr>
              </tbody>
            </table>

            {user.medical_certificate_status === "active" ? (
              <small className="active-tag">Attivo</small>
            ) : (
              <small className="danger-tag">Scaduto</small>
            )}
          </div>
        </>
      ) : (
        <div className="dashboard section-loader">
          <Tailspin size="30" stroke="5" speed="1.5" color="#4f46e5" />
        </div>
      )}

      {createPortal(
        <AnimatePresence>
          {openPortal && (
            <motion.div
              className="delete-portal"
              key="logout-portal"
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              transition={{ duration: 0.35 }}
              onClick={() => setOpenPortal(false)}
            >
              <motion.form
                id="delete-chat"
                onSubmit={logout}
                initial={{ opacity: 0, scale: 0.92, y: 15 }}
                animate={{ opacity: 1, scale: 1, y: 0 }}
                exit={{ opacity: 0, scale: 0.95, y: 8 }}
                transition={{
                  duration: 0.35,
                  ease: "easeOut",
                }}
                onClick={(e) => e.stopPropagation()}
              >
                <div className="trash-logo">
                  <i className="fa-solid fa-right-from-bracket"></i>
                </div>

                <h3 className="delete-title">Effettuare il logout?</h3>

                <p className="delete-text">
                  Ricordati le credenziali per accedere!
                  <br />
                  Se non ti ricordi o non hai salvato le credenziali per
                  riaccedere, contatta la reception per ottenerne delle nuove
                </p>

                <button type="submit" className="delete-btn">
                  <i className="fa-solid fa-right-from-bracket"></i> Logout
                </button>

                <button
                  type="button"
                  className="close-btn"
                  onClick={() => setOpenPortal(false)}
                >
                  Annulla
                </button>
              </motion.form>
            </motion.div>
          )}
        </AnimatePresence>,
        document.body,
      )}
    </>
  );
}
