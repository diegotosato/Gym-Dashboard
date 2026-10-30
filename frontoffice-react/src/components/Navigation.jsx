import { useLocation } from "react-router-dom";
import { Link } from "react-router-dom";

export default function Navigation() {
  let location = useLocation();

  return (
    <header>
      <nav>
        <ul>
          <li>
            <Link
              to={"/dashboard"}
              className={
                location.pathname.includes("dashboard") ? "active-link" : ""
              }
            >
              <i className="fa-solid fa-user"></i>
              Profilo
            </Link>
          </li>
          <li>
            <Link
              to={"/plan"}
              className={
                location.pathname.includes("plan") ? "active-link" : ""
              }
            >
              <i className="fa-solid fa-rectangle-list"></i>
              Scheda
            </Link>
          </li>
          {/* <li>
            <Link
              to={"/timer"}
              className={
                location.pathname.includes("timer") ? "active-link" : ""
              }
            >
              <i className="fa-solid fa-stopwatch"></i>
              Timer
            </Link>
          </li> */}
          <li>
            <Link
              to={"/chat-bot"}
              className={
                location.pathname.includes("chat-bot") ? "active-link" : ""
              }
            >
              <i className="fa-solid fa-robot"></i>
              GymBot
            </Link>
          </li>
        </ul>
      </nav>
    </header>
  );
}
