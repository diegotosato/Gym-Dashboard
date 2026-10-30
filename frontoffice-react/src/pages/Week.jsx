import { useGlobalContext } from "../contexts/GlobalContext";
import { Tailspin } from "ldrs/react";
import "ldrs/react/Tailspin.css";
import { Link, useLocation, useParams } from "react-router-dom";

export default function Plan() {
  const { user, isLoadingUser } = useGlobalContext();

  const params = useParams();
  const { state } = useLocation();

  const workout = user?.workouts?.find(
    (workout) => workout.id === Number(params.workout),
  );

  const week = workout?.weeks?.find(
    (week) => week.week_number === Number(params.week),
  );

  return (
    <>
      <h2 className="section-title">Settimana {params.week}</h2>

      <div className="breadcrumb">
        <Link
          to={`/plan/${params.scheda}/${params.workout}`}
          className="back-link"
        >
          Programma {params.scheda}
        </Link>
        <i className="fa-solid fa-angles-right"></i>
        <p className="active-page">Settimana {params.week}</p>
      </div>

      {!isLoadingUser ? (
        <div className="subtitle">
          <h3>Allenamenti settimanali</h3>

          {week?.sessions.map((session, i) => (
            <Link
              to={`/plan/${params.scheda}/${params.workout}/${params.week}/${i + 1}`}
              className="schedas"
              key={i}
            >
              <p>Allenamento {i + 1}</p>
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
