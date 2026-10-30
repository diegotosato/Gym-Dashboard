import axios from "axios";
import { useEffect, useRef, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import ReactMarkdown from "react-markdown";
import remarkGfm from "remark-gfm";
import { Link } from "react-router-dom";
import { Tailspin } from "ldrs/react";
import { createPortal } from "react-dom";

import { motion, AnimatePresence } from "motion/react";

export default function Chat() {
  const { chat } = useParams();

  const navigate = useNavigate();

  const [message, setMessage] = useState("");
  const [chrono, setChrono] = useState([]);

  const [loader, setLoader] = useState(false);

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  const [openPortal, setOpenPortal] = useState(false);

  async function askAgent(e) {
    e.preventDefault();

    setChrono((prev) => [...prev, { content: message, role: "user" }]);

    setMessage("");
    setLoading(true);
    setError("");

    const request = {
      message: message,
      conversation_id: chat,
    };

    try {
      await axios.post("/api/chat", request).then(async (res) => {
        await axios
          .get(`api/chat/${chat}/history`)
          .then((res) => setChrono(res.data.history));
      });
    } catch (err) {
      setError(err);
    } finally {
      setLoading(false);
    }
  }

  async function deleteChat(e) {
    e.preventDefault();

    try {
      await axios.delete(`/api/chat/${chat}`);
    } catch (err) {
      setError(err);
    } finally {
      navigate("/chat-bot");
    }
  }

  useEffect(() => {
    async function getHistory() {
      try {
        setLoader(true);
        await axios.get(`api/chat/${chat}/history`).then((res) => {
          setChrono(res.data.history);
        });
      } catch (err) {
        console.log(err);
      } finally {
        setLoader(false);
      }
    }

    getHistory();
  }, []);

  const containerRef = useRef(null);

  // Nuovi messaggi: scroll fluido
  useEffect(() => {
    const el = containerRef.current;
    if (el && !loader) {
      el.scrollTo({ top: el.scrollHeight, behavior: "smooth" });
    }
  }, [chrono, loading]);

  // Fine caricamento history: salto istantaneo in fondo
  useEffect(() => {
    const el = containerRef.current;
    if (el && !loader) {
      el.scrollTo({ top: el.scrollHeight, behavior: "instant" });
    }
  }, [loader]);

  return (
    <>
      <div>
        <div
          style={{
            display: "flex",
            justifyContent: "space-between",
            alignItems: "center",
            marginBottom: "20px",
          }}
        >
          <Link to={"/chat-bot"} className="back-link">
            <i className="fa-solid fa-angle-left"></i> Torna indietro
          </Link>

          <button className="delete-btn-sm" onClick={() => setOpenPortal(true)}>
            <i className="fa-solid fa-circle-xmark"></i> Cancella chat
          </button>
        </div>
      </div>

      <div className="subtitle exercise-container" ref={containerRef}>
        {!loader ? (
          <div className="chat-box">
            {chrono?.map((txt, i) => (
              <div className="chat-message" key={i}>
                {txt.role === "assistant" && (
                  <div className="chat-bot-icon">
                    <i className="fa-solid fa-robot"></i>
                  </div>
                )}
                {txt.role === "assistant" ? (
                  <div className="bot-message">
                    <ReactMarkdown remarkPlugins={[remarkGfm]}>
                      {txt.content}
                    </ReactMarkdown>
                  </div>
                ) : (
                  <div className="user-message">
                    <ReactMarkdown remarkPlugins={[remarkGfm]}>
                      {txt.content}
                    </ReactMarkdown>
                  </div>
                )}
              </div>
            ))}
          </div>
        ) : (
          <div className="dashboard section-loader">
            <Tailspin size="30" stroke="5" speed="1.5" color="#4f46e5" />
          </div>
        )}
      </div>

      <form onSubmit={askAgent} id="form-send-message">
        {!loading ? (
          <input
            className="box-message"
            name="message"
            id="message"
            type="text"
            placeholder="Es. Cosa posso fare al posto delle alzate?"
            value={message}
            onChange={(e) => setMessage(e.target.value)}
          />
        ) : (
          <input
            className="box-message-disabled"
            name="message"
            id="message"
            type="text"
            placeholder="Sto pensando ..."
            value={message}
            onChange={(e) => setMessage(e.target.value)}
            disabled
          />
        )}

        {!loading ? (
          <button className="send-btn" type="submit">
            <i className="fa-solid fa-paper-plane"></i>
          </button>
        ) : (
          <button className="send-btn-disabled" type="submit" disabled>
            <Tailspin size="30" stroke="5" speed="1.5" color="#3b3b3b4d" />
          </button>
        )}
      </form>

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
                onSubmit={deleteChat}
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
                  <i className="fa-solid fa-trash"></i>
                </div>

                <h3 className="delete-title">Eliminare la conversazione?</h3>

                <p className="delete-text">
                  Sei sicuro di voler eliminare questa chat?
                  <br />
                  Questa azione non può essere annullata e tutti i messaggi
                  verranno rimossi definitivamente
                </p>

                <button type="submit" className="delete-btn">
                  <i className="fa-solid fa-circle-xmark"></i> Cancella chat
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
