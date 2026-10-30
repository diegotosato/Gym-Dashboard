import axios from "axios";
import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { Tailspin } from "ldrs/react";

export default function ChatBot() {
  const [allChats, setAllChats] = useState(null);

  const [loader, setLoader] = useState(false);

  useEffect(() => {
    async function getAllChats() {
      try {
        setLoader(true);
        await axios.get("api/chat/conversations").then((res) => {
          setAllChats(res.data);
        });
      } catch (err) {
        console.log(err);
      } finally {
        setLoader(false);
      }
    }

    getAllChats();
  }, []);

  return (
    <>
      <div className="bot-intro">
        <div className="bot-logo">
          <i className="fa-solid fa-robot"></i>
        </div>

        <div className="bot-description">
          <h2>GymBot</h2>
          <p>Il tuo assistente AI specializzato in palestra</p>
          <small>
            Chiedi suggerimenti su esercizi sostitutivi di altri già presenti
            nella tua scheda di allenamento
          </small>
        </div>

        <Link to={"/chat-bot/new-chat"} className="new-chat-btn">
          <i className="fa-solid fa-circle-plus"></i> Nuova chat
        </Link>
      </div>

      <b className="my-chats">Le tue chat</b>

      {!loader ? (
        <div className="chat-container chat-list">
          {allChats?.length < 1 ? (
            <em className="empty-chats">
              Nessuna chat avviata, avviane una nuova
            </em>
          ) : (
            <>
              {allChats?.map((chat) => (
                <Link to={`/chat-bot/${chat.id}`} key={chat.id}>
                  <div className="chat-logo">
                    <i className="fa-solid fa-comment-dots"></i>
                  </div>

                  {chat.title}

                  <i className="fa-solid fa-angle-right chat-link-arrow"></i>
                </Link>
              ))}
            </>
          )}
        </div>
      ) : (
        <div className="dashboard section-loader">
          <Tailspin size="30" stroke="5" speed="1.5" color="#4f46e5" />
        </div>
      )}
    </>
  );
}
