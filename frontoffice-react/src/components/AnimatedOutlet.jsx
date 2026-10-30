import { AnimatePresence, motion } from "motion/react";
import { useLocation, useOutlet } from "react-router-dom";

export default function AnimatedOutlet() {
  const location = useLocation();
  const outlet = useOutlet();

  return (
    <AnimatePresence mode="wait" initial={false}>
      <motion.div
        key={location.pathname}
        initial={{ opacity: 0, x: 8 }}
        animate={{ opacity: 1, x: 0 }}
        exit={{ opacity: 0, x: -8 }}
        transition={{ duration: 0.25, ease: "easeInOut" }}
        className="motion-div"
        // style={{ width: "100%" }}
      >
        {outlet}
      </motion.div>
    </AnimatePresence>
  );
}
