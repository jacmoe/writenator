(ns jacmoe.writenator.core
  (:require
    [clojure.tools.logging :as log]
    [integrant.core :as ig]
    [jacmoe.writenator.config :as config]
    [jacmoe.writenator.env :refer [defaults]]

    ;; Edges       
    [kit.edge.server.undertow]
    [jacmoe.writenator.web.handler]

    ;; Routes
    [jacmoe.writenator.web.routes.api]
    
    [jacmoe.writenator.web.routes.pages])
  (:gen-class))

;; log uncaught exceptions in threads
(Thread/setDefaultUncaughtExceptionHandler
  (reify Thread$UncaughtExceptionHandler
    (uncaughtException [_ thread ex]
      (log/error {:what :uncaught-exception
                  :exception ex
                  :where (str "Uncaught exception on" (.getName thread))}))))

(defonce system (atom nil))

(defn stop-app []
  ((or (:stop defaults) (fn [])))
  (some-> (deref system) (ig/halt!))
  (shutdown-agents))

(defn start-app [& [params]]
  ((or (:start params) (:start defaults) (fn [])))
  (->> (config/system-config (or (:opts params) (:opts defaults) {}))
       (ig/prep)
       (ig/init)
       (reset! system))
  (.addShutdownHook (Runtime/getRuntime) (Thread. stop-app)))

(defn -main [& _]
  (start-app))
