import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import LanguageDetector from 'i18next-browser-languagedetector';

i18n
    .use(LanguageDetector)
    .use(initReactI18next)
    .init({
            resources: {
                en: {
                    translation: {
                        login: "Login",
                        register: "Register",
                        email: "Email",
                        password: "Password",
                        need_account: "Need an account? Register",
                        already_have_account: "Already have an account? Login",
                    }
                },
                fr: {
                    translation: {
                        login: "Connexion",
                        register: "S'inscrire",
                        email: "Email",
                        password: "Mot de passe",
                        need_account: "Besoin d'un compte ? S'inscrire",
                        already_have_account: "Déjà un compte ? Connexion",
                    }
                }
        },
        fallbackLng: "en",
        detection: {
            order: ['navigator', 'localStorage', 'cookie'],
            caches: ['localStorage']
        },
        interpolation: {
            escapeValue: false
        }
    });

export default i18n;
