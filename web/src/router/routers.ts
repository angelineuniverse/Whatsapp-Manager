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

const lazyWrap = (factory: () => Promise<any>) => {
    return async () => {
        const page = await factory()
        return {
            Component: page.default || page.Component,
            ErrorBoundary: page.ErrorBoundary,
            // loader: page.loader,
        }
    }
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
        lazy: lazyWrap(() => import('../layout/dashboard')),
        async loader() {
            return authNotExist();
        },
        children: [
            {
                path: 'master',
                children: [
                    {
                        path: 'akses',
                        lazy: lazyWrap(() => import('../layout/base')),
                        children: [
                            {
                                id: 'akses-index',
                                path: '',
                                lazy: lazyWrap(() => import('../layout/master/akses/index')),
                            },
                            {
                                id: 'akses-add',
                                path: 'add',
                                lazy: lazyWrap(() => import('../layout/master/akses/add')),
                            },
                            {
                                id: 'akses-show',
                                path: 'show/:id',
                                lazy: lazyWrap(() => import('../layout/master/akses/show')),
                            },
                        ]
                    },
                    {
                        path: 'pengguna',
                        lazy: lazyWrap(() => import('../layout/base')),
                        children: [
                            {
                                id: 'pengguna-index',
                                path: '',
                                lazy: lazyWrap(() => import('../layout/master/pengguna/index')),
                            },
                            {
                                id: 'pengguna-add',
                                path: 'add',
                                lazy: lazyWrap(() => import('../layout/master/pengguna/add')),
                            },
                        ]
                    },
                    
                ]
            }
        ]
    },
]);

export default Router;