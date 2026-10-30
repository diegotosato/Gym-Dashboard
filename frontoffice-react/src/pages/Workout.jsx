import { useGlobalContext } from "../contexts/GlobalContext";
import { Tailspin } from "ldrs/react";
import "ldrs/react/Tailspin.css";
import { Link, useParams } from "react-router-dom";

export default function Plan() {
  const { user, isLoadingUser } = useGlobalContext();

  const params = useParams();

  const workout = user?.workouts?.find(
    (workout) => workout.id === Number(params.workout),
  );

  return (
    <>
      <h2 className="section-title">Programma {params.scheda}</h2>

      <Link to={"/plan"} className="back-link">
        <i className="fa-solid fa-angle-left"></i> Torna indietro
      </Link>

      {!isLoadingUser ? (
        <div className="subtitle">
          {workout?.weeks.map((week, i) => (
            <Link
              to={`/plan/${params.scheda}/${params.workout}/${String(week?.week_number)}`}
              className="schedas"
              state={i + 1}
              key={i}
            >
              <p>Settimana {week?.week_number}</p>
              <i className="fa-solid fa-angle-right"></i>
            </Link>
          ))}
        </div>
      ) : (
        <div className="dashboard section-loader">
          <Tailspin size="30" stroke="5" speed="1.5" color="#4f46e5" />
        </div>
      )}
    </>
  );
}
