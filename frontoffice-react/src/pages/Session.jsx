import { useGlobalContext } from "../contexts/GlobalContext";
import { Tailspin } from "ldrs/react";
import "ldrs/react/Tailspin.css";
import { Link, useLocation, useParams } from "react-router-dom";

export default function Plan() {
  const { user, isLoadingUser } = useGlobalContext();

  const params = useParams();
  let sessionIndex = Number(params.session);

  const workout = user?.workouts?.find(
    (workout) => workout.id === Number(params.workout),
  );

  const week = workout?.weeks?.find(
    (week) => week.week_number === Number(params.week),
  );

  const session = week?.sessions[sessionIndex - 1];

  return (
    <>
      <h2 className="section-title">Allenamento {params.session}</h2>

      <div className="breadcrumb">
        <Link
          to={`/plan/${params.scheda}/${params.workout}`}
          className="back-link"
        >
          Programma {params.scheda}
        </Link>
        <i className="fa-solid fa-angles-right"></i>
        <Link
          to={`/plan/${params.scheda}/${params.workout}/${params.week}`}
          className="back-link"
        >
          Settimana {params.week}
        </Link>
        <i className="fa-solid fa-angles-right"></i>
        <p className="active-page">Allenamento {params.session}</p>
      </div>
      {/* <Link
        to={`/plan/${params.scheda}/${params.workout}/${params.week}`}
        className="back-link"
      >
        <i className="fa-solid fa-angle-left"></i> Torna indietro
      </Link> */}

      <h3>Esercizi</h3>

      <div className="subtitle exercise-container">
        {!isLoadingUser ? (
          <>
            {session?.exercises.map((ex) => (
              <div className="exercise-tag" key={ex.id}>
                <p className="exercise-name">{ex.exercise_name}</p>
                <div className="exercise-infos">
                  <p className="exercise-i">
                    Serie: <b>{ex.sets ? ex.sets : "0"}</b>
                  </p>
                  <p className="exercise-i">
                    Ripetizioni: <b>{ex.reps ? ex.reps : "0"}</b>
                  </p>
                  <p className="exercise-i">
                    Peso: <b>{ex.weight ? ex.weight : "0"}</b>
                  </p>
                </div>
                <p className="exercise-notes">
                  Note: <b>{ex.notes ? ex.notes : "Nessuna nota"}</b>
                </p>
              </div>
            ))}
          </>
        ) : (
          <div className="dashboard section-loader">
            <Tailspin size="30" stroke="5" speed="1.5" color="#4f46e5" />
          </div>
        )}
      </div>
    </>
  );
}
