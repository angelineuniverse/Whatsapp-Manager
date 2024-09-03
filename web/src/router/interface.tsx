import React from "react";
import {
  Location,
  NavigateFunction,
  Params,
  useLocation,
  useNavigate,
  useParams,
} from "react-router-dom";

export interface RouterInterface {
  navigate: NavigateFunction;
  location: Location;
  readonly params: Params<string>;
}

export const withRouterInterface = (Component: React.ComponentType<any>) => {
  return (props: any) => {
    const location = useLocation();
    const navigate = useNavigate();
    const params = useParams();
    return (
      <Component
        navigate={navigate}
        location={location}
        params={params}
        {...props}
      />
    );
  };
};
