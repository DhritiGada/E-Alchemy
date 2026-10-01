import React, { useMemo, useState } from "react";
import { createRoot } from "react-dom/client";
import {
  ArrowRight,
  BookOpen,
  BrainCircuit,
  Check,
  CheckCircle2,
  ChevronRight,
  CircleAlert,
  GraduationCap,
  Plus,
  RotateCcw,
  Sparkles,
  Target,
  Trash2,
  WandSparkles,
} from "lucide-react";
import "./styles.css";

const courses = [
  { id: "IT101", name: "Programming Foundations", credits: 3, difficulty: 2, category: "Core", prereqs: [] },
  { id: "IT120", name: "Discrete Mathematics", credits: 3, difficulty: 3, category: "Core", prereqs: [] },
  { id: "IT201", name: "Object-Oriented Programming", credits: 4, difficulty: 3, category: "Core", prereqs: ["IT101"] },
  { id: "IT215", name: "Database Management Systems", credits: 4, difficulty: 3, category: "Data", prereqs: ["IT101"] },
  { id: "IT230", name: "Computer Networks", credits: 3, difficulty: 3, category: "Systems", prereqs: ["IT101"] },
  { id: "IT260", name: "Human Computer Interaction", credits: 3, difficulty: 2, category: "Product", prereqs: ["IT101"] },
  { id: "IT301", name: "Data Structures & Algorithms", credits: 4, difficulty: 4, category: "Core", prereqs: ["IT201", "IT120"] },
  { id: "IT315", name: "Cloud Computing", credits: 3, difficulty: 4, category: "Systems", prereqs: ["IT230"] },
  { id: "IT325", name: "Data Analytics", credits: 3, difficulty: 3, category: "Data", prereqs: ["IT215"] },
  { id: "IT340", name: "Software Engineering", credits: 4, difficulty: 4, category: "Core", prereqs: ["IT201", "IT215"] },
  { id: "IT360", name: "Product Analytics", credits: 3, difficulty: 3, category: "Product", prereqs: ["IT215", "IT260"] },
  { id: "IT410", name: "Applied Artificial Intelligence", credits: 4, difficulty: 5, category: "AI", prereqs: ["IT301", "IT325"] },
  { id: "IT430", name: "Distributed Systems", credits: 4, difficulty: 5, category: "Systems", prereqs: ["IT315", "IT340"] },
  { id: "IT450", name: "Capstone Product Studio", credits: 4, difficulty: 4, category: "Capstone", prereqs: ["IT340", "IT360"] },
];

const initialCompleted = ["IT101", "IT120", "IT201", "IT215", "IT230", "IT260"];

function courseById(id) {
  return courses.find((course) => course.id === id);
}

function difficultyLabel(value) {
  if (value <= 2.3) return "Light";
  if (value <= 3.4) return "Balanced";
  if (value <= 4.2) return "Challenging";
  return "Heavy";
}

function RecommendationReason({ course, completed, plan }) {
  const unlocked = courses.filter((candidate) =>
    candidate.prereqs.includes(course.id) &&
    candidate.prereqs.every((p) => completed.includes(p) || plan.includes(p) || p === course.id)
  );

  return (
    <div className="reason-card">
      <div className="reason-icon"><BrainCircuit size={18} /></div>
      <div>
        <strong>Why {course.id}?</strong>
        <p>
          You have completed all {course.prereqs.length || "required"} prerequisites.
          {unlocked.length
            ? ` It also moves you toward ${unlocked.slice(0, 2).map((c) => c.name).join(" and ")}.`
            : " It keeps your degree path moving without adding a prerequisite conflict."}
        </p>
      </div>
    </div>
  );
}

function App() {
  const [completed, setCompleted] = useState(initialCompleted);
  const [plan, setPlan] = useState([]);
  const [creditTarget, setCreditTarget] = useState(13);
  const [focus, setFocus] = useState("Balanced");
  const [selectedReason, setSelectedReason] = useState(null);

  const completedCredits = useMemo(
    () => completed.reduce((sum, id) => sum + (courseById(id)?.credits || 0), 0),
    [completed]
  );

  const planCourses = plan.map(courseById).filter(Boolean);
  const plannedCredits = planCourses.reduce((sum, course) => sum + course.credits, 0);
  const plannedDifficulty = planCourses.length
    ? planCourses.reduce((sum, course) => sum + course.difficulty, 0) / planCourses.length
    : 0;

  const eligible = useMemo(() => {
    return courses.filter((course) => {
      if (completed.includes(course.id) || plan.includes(course.id)) return false;
      return course.prereqs.every((id) => completed.includes(id));
    });
  }, [completed, plan]);

  const recommendations = useMemo(() => {
    const weighted = [...eligible].sort((a, b) => {
      const focusBoost = (course) => {
        if (focus === "AI & Data" && ["AI", "Data"].includes(course.category)) return 4;
        if (focus === "Product" && course.category === "Product") return 4;
        if (focus === "Systems" && course.category === "Systems") return 4;
        if (focus === "Balanced" && ["Core", "Data", "Product", "Systems"].includes(course.category)) return 2;
        return 0;
      };

      const unlockScore = (course) =>
        courses.filter((candidate) => candidate.prereqs.includes(course.id)).length * 2;

      return (focusBoost(b) + unlockScore(b) - b.difficulty * 0.3) -
        (focusBoost(a) + unlockScore(a) - a.difficulty * 0.3);
    });

    return weighted.slice(0, 4);
  }, [eligible, focus]);

  const progress = Math.round((completedCredits / 52) * 100);

  function generatePlan() {
    const sorted = [...courses]
      .filter((course) => !completed.includes(course.id))
      .sort((a, b) => {
        const readyA = a.prereqs.every((p) => completed.includes(p));
        const readyB = b.prereqs.every((p) => completed.includes(p));
        if (readyA !== readyB) return readyA ? -1 : 1;

        const focusScore = (course) => {
          if (focus === "AI & Data" && ["AI", "Data"].includes(course.category)) return 4;
          if (focus === "Product" && course.category === "Product") return 4;
          if (focus === "Systems" && course.category === "Systems") return 4;
          return course.category === "Core" ? 2 : 1;
        };
        return focusScore(b) - focusScore(a);
      });

    const next = [];
    let credits = 0;

    for (const course of sorted) {
      const prereqsMet = course.prereqs.every(
        (p) => completed.includes(p) || next.includes(p)
      );
      if (!prereqsMet) continue;
      if (credits + course.credits > creditTarget + 1) continue;

      next.push(course.id);
      credits += course.credits;

      if (credits >= creditTarget - 2) break;
    }

    setPlan(next);
    setSelectedReason(next[0] || null);
  }

  function toggleCompleted(id) {
    setCompleted((current) =>
      current.includes(id) ? current.filter((item) => item !== id) : [...current, id]
    );
    setPlan((current) => current.filter((item) => item !== id));
  }

  function addToPlan(id) {
    if (!plan.includes(id)) {
      setPlan([...plan, id]);
      setSelectedReason(id);
    }
  }

  return (
    <main className="app-shell">
      <header className="topbar">
        <a className="brand" href="#">
          <div className="brand-mark"><GraduationCap size={21} /></div>
          <div>
            <strong>E-Alchemy</strong>
            <span>Planner</span>
          </div>
        </a>
        <div className="topbar-right">
          <span className="prototype-pill">Interactive prototype</span>
          <div className="avatar">DG</div>
        </div>
      </header>

      <section className="hero">
        <div className="hero-copy">
          <span className="eyebrow"><Sparkles size={14} /> SMART SEMESTER PLANNING</span>
          <h1>Build a semester that<br /><em>actually makes sense.</em></h1>
          <p>
            E-Alchemy checks prerequisites, degree progress, workload, and your
            academic focus before suggesting what to take next.
          </p>
          <button className="primary-cta" onClick={generatePlan}>
            <WandSparkles size={18} /> Build my semester
          </button>
        </div>

        <div className="progress-card">
          <div className="progress-header">
            <span>Degree progress</span>
            <strong>{progress}%</strong>
          </div>
          <div className="progress-track">
            <div className="progress-fill" style={{ width: `${Math.min(progress, 100)}%` }} />
          </div>
          <div className="progress-stats">
            <div><strong>{completedCredits}</strong><span>credits completed</span></div>
            <div><strong>{52 - completedCredits}</strong><span>credits remaining</span></div>
            <div><strong>{completed.length}</strong><span>courses complete</span></div>
          </div>
        </div>
      </section>

      <section className="planner-grid">
        <aside className="panel setup-panel">
          <div className="panel-heading">
            <div>
              <span className="section-kicker">STEP 1</span>
              <h2>Set your semester</h2>
            </div>
            <Target size={22} />
          </div>

          <label className="field-label">Academic focus</label>
          <div className="focus-options">
            {["Balanced", "AI & Data", "Product", "Systems"].map((option) => (
              <button
                key={option}
                className={focus === option ? "focus-chip active" : "focus-chip"}
                onClick={() => setFocus(option)}
              >
                {option}
              </button>
            ))}
          </div>

          <label className="field-label credit-label">
            Target credits
            <span>{creditTarget} credits</span>
          </label>
          <input
            className="credit-range"
            type="range"
            min="8"
            max="18"
            value={creditTarget}
            onChange={(event) => setCreditTarget(Number(event.target.value))}
          />
          <div className="range-labels"><span>8</span><span>18</span></div>

          <button className="generate-button" onClick={generatePlan}>
            <WandSparkles size={17} />
            Generate recommendation
          </button>

          <div className="tiny-note">
            <CircleAlert size={15} />
            Recommendations are planning guidance, not official academic advising.
          </div>
        </aside>

        <section className="panel plan-panel">
          <div className="panel-heading">
            <div>
              <span className="section-kicker">STEP 2</span>
              <h2>Your semester plan</h2>
            </div>
            {plan.length > 0 && (
              <button className="ghost-button" onClick={() => setPlan([])}>
                <RotateCcw size={15} /> Reset
              </button>
            )}
          </div>

          {plan.length === 0 ? (
            <div className="empty-plan">
              <div className="empty-icon"><BookOpen size={27} /></div>
              <h3>No courses planned yet</h3>
              <p>Choose your focus and credit target, then generate a semester recommendation.</p>
              <button onClick={generatePlan}>Generate my plan <ArrowRight size={16} /></button>
            </div>
          ) : (
            <>
              <div className="plan-summary">
                <div><span>Credits</span><strong>{plannedCredits}</strong></div>
                <div><span>Workload</span><strong>{difficultyLabel(plannedDifficulty)}</strong></div>
                <div><span>Prerequisites</span><strong className="success-text"><Check size={15} /> Clear</strong></div>
              </div>

              <div className="course-stack">
                {planCourses.map((course, index) => (
                  <article
                    key={course.id}
                    className={selectedReason === course.id ? "plan-course selected" : "plan-course"}
                    onClick={() => setSelectedReason(course.id)}
                  >
                    <div className="course-number">{String(index + 1).padStart(2, "0")}</div>
                    <div className="course-main">
                      <div className="course-title-row">
                        <strong>{course.name}</strong>
                        <span className={`category-tag ${course.category.toLowerCase().replaceAll(" ", "-").replaceAll("&", "and")}`}>
                          {course.category}
                        </span>
                      </div>
                      <div className="course-meta">
                        <span>{course.id}</span>
                        <span>•</span>
                        <span>{course.credits} credits</span>
                        <span>•</span>
                        <span>Difficulty {course.difficulty}/5</span>
                      </div>
                    </div>
                    <button
                      className="icon-button"
                      title="Remove from plan"
                      onClick={(event) => {
                        event.stopPropagation();
                        setPlan(plan.filter((id) => id !== course.id));
                      }}
                    >
                      <Trash2 size={16} />
                    </button>
                  </article>
                ))}
              </div>

              {selectedReason && courseById(selectedReason) && (
                <RecommendationReason
                  course={courseById(selectedReason)}
                  completed={completed}
                  plan={plan}
                />
              )}
            </>
          )}
        </section>

        <aside className="panel recommendation-panel">
          <div className="panel-heading">
            <div>
              <span className="section-kicker">NEXT BEST</span>
              <h2>Recommended now</h2>
            </div>
            <Sparkles size={21} />
          </div>

          <p className="panel-description">
            Eligible courses ranked by your focus, prerequisite impact, and workload.
          </p>

          <div className="recommendation-list">
            {recommendations.map((course) => (
              <article className="recommendation" key={course.id}>
                <div className="rec-topline">
                  <span className="course-code">{course.id}</span>
                  <span>{course.credits} cr</span>
                </div>
                <strong>{course.name}</strong>
                <p>
                  {course.prereqs.length
                    ? `Prerequisites complete: ${course.prereqs.join(", ")}`
                    : "No prerequisites required"}
                </p>
                <button onClick={() => addToPlan(course.id)}>
                  <Plus size={15} /> Add to plan
                </button>
              </article>
            ))}
          </div>
        </aside>
      </section>

      <section className="completed-section">
        <div className="completed-heading">
          <div>
            <span className="section-kicker">ACADEMIC HISTORY</span>
            <h2>Completed courses</h2>
            <p>Toggle courses to simulate a different academic history.</p>
          </div>
          <div className="completed-count">
            <CheckCircle2 size={19} />
            {completed.length} completed
          </div>
        </div>

        <div className="catalog-grid">
          {courses.map((course) => {
            const isCompleted = completed.includes(course.id);
            const blocked = !isCompleted && course.prereqs.some((p) => !completed.includes(p));
            return (
              <button
                key={course.id}
                className={isCompleted ? "catalog-course completed" : "catalog-course"}
                onClick={() => toggleCompleted(course.id)}
              >
                <div className="catalog-status">
                  <span className="checkbox">{isCompleted && <Check size={14} />}</span>
                  <span className="course-code">{course.id}</span>
                </div>
                <strong>{course.name}</strong>
                <div className="catalog-footer">
                  <span>{course.credits} credits</span>
                  {blocked ? <span className="blocked">Prereq locked</span> : <ChevronRight size={15} />}
                </div>
              </button>
            );
          })}
        </div>
      </section>

      <footer>
        <div className="brand footer-brand">
          <div className="brand-mark"><GraduationCap size={18} /></div>
          <strong>E-Alchemy Planner</strong>
        </div>
        <p>Explainable degree planning prototype. No student records are stored.</p>
      </footer>
    </main>
  );
}

createRoot(document.getElementById("root")).render(<App />);
