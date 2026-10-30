import axios from "axios";
import { useEffect, useRef, useState } from "react";
import { Link } from "react-router-dom";
import ReactMarkdown from "react-markdown";
import remarkGfm from "remark-gfm";
import { Tailspin } from "ldrs/react";

export default function ChatBot() {
  const [message, setMessage] = useState("");
  const [conversationID, setConversationID] = useState(null);

  const [loader, setLoader] = useState(false);

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  const [chrono, setChrono] = useState([]);

  async function askAgent(e) {
    e.preventDefault();

    setChrono((prev) => [...prev, { content: message, role: "user" }]);

    setMessage("");
    setLoading(true);
    setError("");

    const request = {
      message: message,
      conversation_id: conversationID,
    };

    try {
      await axios.post("/api/chat", request).then(async (res) => {
        setConversationID(res.data.conversation_id);

        const newConversationID = res.data.conversation_id;

        if (newConversationID) {
          const response = await axios.get(
            `/api/chat/${newConversationID}/history`,
          );

          const lastBotResponse = response.data.history;

          setChrono(lastBotResponse);
        }
      });
    } catch (err) {
      setError(err);
    } finally {
      setLoading(false);
    }
  }

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
      <h2 className="section-title">Nuova chat</h2>

      <Link
        to={"/chat-bot"}
        className="back-link"
        style={{ marginBottom: "10px" }}
      >
        <i className="fa-solid fa-angle-left"></i> Torna indietro
      </Link>

      <div className="subtitle exercise-container" ref={containerRef}>
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
    </>
  );
}
