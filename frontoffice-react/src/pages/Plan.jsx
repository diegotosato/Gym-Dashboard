import { useGlobalContext } from "../contexts/GlobalContext";
import { Tailspin } from "ldrs/react";
import "ldrs/react/Tailspin.css";
import { Link } from "react-router-dom";

export default function Plan() {
  const { user, isLoadingUser } = useGlobalContext();

  return (
    <>
      <h2 className="section-title">Programmi di allenamento</h2>

      {!isLoadingUser ? (
        <>
          {user?.workouts ? (
            user?.workouts.map((workout, i) => (
              <Link
                to={`/plan/${i + 1}/${workout.id}`}
                className="schedas"
                key={i}
              >
                <p>Programma {i + 1}</p>
                <i className="fa-solid fa-angle-right"></i>
              </Link>
            ))
          ) : (
            <em className="empty-chats">
              Ancora nessun programma di allenamento inserito, contatta il
              personale per ottenere informazioni
            </em>
          )}
        </>
      ) : (
        <div className="dashboard section-loader">
          <Tailspin size="30" stroke="5" speed="1.5" color="#4f46e5" />
        </div>
      )}
    </>
  );
}
