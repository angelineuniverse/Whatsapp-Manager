import { createBrowserRouter, redirect } from "react-router-dom";
import { getCookie } from "typescript-cookie";

export function authNotExist() {
    const token = getCookie('LOG');
    if (!token) {
        return redirect('/auth')
    }
    return null;
}

export function authExist() {
    const token = getCookie('LOG');
    if (token) {
        return redirect('/')
    }
    return null;
}

const Router = createBrowserRouter([
    {
        path: '/auth',
        async lazy() {
            let Auth = await import('../layout/auth/index');
            return { Component: Auth.default };
        },
        async loader() {
            return authExist();
        }
    },
    {
        path: '/',
        async lazy() {
            let Dashboard = await import('../layout/dashboard');
            return { Component: Dashboard.default };
        },
        async loader() {
            return authNotExist();
        }
    }
]);

export default Router;