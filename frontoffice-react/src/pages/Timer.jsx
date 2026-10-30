import { useGlobalContext } from "../contexts/GlobalContext";
import { Tailspin } from "ldrs/react";
import "ldrs/react/Tailspin.css";
import { useRef, useState } from "react";

export default function Timer() {
  const { user, isLoadingUser } = useGlobalContext();

  const [counter, setCounter] = useState(0);
  const [isWorking, setIsWorking] = useState(false);
  const intervalIdRef = useRef(null);

  function onStart() {
    setIsWorking(true);
    intervalIdRef.current = setInterval(() => {
      setCounter((prev) => prev + 0.01);
    }, 10);
  }

  function onPause() {
    clearInterval(intervalIdRef.current);
  }

  function reset() {
    setIsWorking(false);
    setCounter(0);
  }

  function getMinuts() {
    return String(Math.floor(counter / 60)).padStart(2, "0");
  }

  function getSec() {
    return String(Math.floor(counter % 60)).padStart(2, "0");
  }

  function getMil() {
    return String(Math.floor((counter % 1) * 100)).padStart(2, "0");
  }

  return (
    <section className="dashboard">
      <h2 className="section-title">Timer</h2>

      <div className="timer">
        <p className="numbers">
          {getMinuts()}:{getSec()}:{getMil()}
        </p>
      </div>

      {isWorking === false ? (
        <div className="buttons">
          <button className="timer-button" onClick={() => onStart()}>
            <i className="fa-solid fa-play"></i>
          </button>
        </div>
      ) : (
        <div className="buttons">
          <button className="timer-button" onClick={() => onPause()}>
            <i className="fa-solid fa-pause"></i>
          </button>
          <button className="timer-button" onClick={() => reset()}>
            <i className="fa-solid fa-rotate-left"></i>
          </button>
        </div>
      )}
    </section>
  );
}
