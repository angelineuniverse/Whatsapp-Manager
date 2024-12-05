import { createBrowserRouter, redirect } from "react-router-dom";
import { getCookie } from "typescript-cookie";

export function authNotExist() {
  const token = getCookie("LOG");
  if (!token) {
    return redirect("/auth");
  }
  return null;
}

export function authExist() {
  const token = getCookie("LOG");
  if (token) {
    return redirect("/");
  }
  return null;
}

const lazyWrap = (factory: () => Promise<any>) => {
  return async () => {
    const page = await factory();
    return {
      Component: page.default || page.Component,
      ErrorBoundary: page.ErrorBoundary,
      // loader: page.loader,
    };
  };
};

const Router = createBrowserRouter([
  {
    path: "/auth",
    async lazy() {
      let Auth = await import("../layout/auth/index");
      return { Component: Auth.default };
    },
    async loader() {
      return authExist();
    },
  },
  {
    path: "/",
    lazy: lazyWrap(() => import("../layout/dashboard")),
    async loader() {
      return authNotExist();
    },
    children: [
      {
        path: "master",
        children: [
          {
            path: "roles",
            lazy: lazyWrap(() => import("../layout/base")),
            children: [
              {
                id: "roles-index",
                path: "",
                lazy: lazyWrap(() => import("../layout/master/roles/index")),
              },
              {
                id: "roles-add",
                path: "add",
                lazy: lazyWrap(() => import("../layout/master/roles/add")),
              },
              {
                id: "roles-show",
                path: "show/:id",
                lazy: lazyWrap(() => import("../layout/master/roles/show")),
              },
            ],
          },
          {
            path: "pengguna",
            lazy: lazyWrap(() => import("../layout/base")),
            children: [
              {
                id: "pengguna-index",
                path: "",
                lazy: lazyWrap(() => import("../layout/master/pengguna/index")),
              },
              {
                id: "pengguna-add",
                path: "add",
                lazy: lazyWrap(() => import("../layout/master/pengguna/add")),
              },
              {
                id: "pengguna-show",
                path: "show/:id",
                lazy: lazyWrap(() => import("../layout/master/pengguna/show")),
              },
            ],
          },
          {
            path: "project",
            lazy: lazyWrap(() => import("../layout/base")),
            children: [
              {
                id: "project-index",
                path: "",
                lazy: lazyWrap(() => import("../layout/master/project/index")),
              },
              {
                id: "project-add",
                path: "add",
                lazy: lazyWrap(() => import("../layout/master/project/add")),
              },
              {
                id: "project-show",
                path: "show/:id",
                lazy: lazyWrap(() => import("../layout/master/project/show")),
              },
            ],
          },
          {
            path: "unit",
            lazy: lazyWrap(() => import("../layout/base")),
            children: [
              {
                id: "unit-index",
                path: "",
                lazy: lazyWrap(() => import("../layout/master/unit/index")),
                children: [
                  {
                    id: "unit-list-index",
                    path: "",
                    index: true,
                    lazy: lazyWrap(
                      () => import("../layout/master/unit/list/index")
                    ),
                  },
                  {
                    id: "unit-status-index",
                    path: "status",
                    lazy: lazyWrap(
                      () => import("../layout/master/unit/status/index")
                    ),
                  },
                  {
                    id: "unit-type-index",
                    path: "type",
                    lazy: lazyWrap(
                      () => import("../layout/master/unit/type/index")
                    ),
                  },
                ],
              },
              {
                id: "unit-status-add",
                path: "status/add",
                lazy: lazyWrap(
                  () => import("../layout/master/unit/status/add")
                ),
              },
              {
                id: "unit-status-show",
                path: "status/show/:id",
                lazy: lazyWrap(
                  () => import("../layout/master/unit/status/show")
                ),
              },
              {
                id: "unit-type-add",
                path: "type/add",
                lazy: lazyWrap(() => import("../layout/master/unit/type/add")),
              },
              {
                id: "unit-type-show",
                path: "type/show/:id",
                lazy: lazyWrap(() => import("../layout/master/unit/type/show")),
              },
              {
                id: "unit-list-add",
                path: "list/add",
                lazy: lazyWrap(() => import("../layout/master/unit/list/add")),
              },
              {
                id: "unit-list-show",
                path: "list/show/:id",
                lazy: lazyWrap(() => import("../layout/master/unit/list/show")),
              },
            ],
          },
        ],
      },
      {
        path: "profile",
        lazy: lazyWrap(() => import("../layout/profile/index")),
      },
    ],
  },
]);

export default Router;
