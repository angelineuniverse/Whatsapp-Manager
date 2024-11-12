import { Component } from "react";
import { NavLink, Outlet } from "react-router-dom";
import { Icon } from "@angelineuniverse/design";
import { RouterInterface } from "../router/interface";
import { MenuIndex } from "./controller";

class Dashboard extends Component<RouterInterface> {
  state: Readonly<{
    menuList: Array<any>;
  }>;
  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      menuList: [],
    };

    this.callMenuIndex = this.callMenuIndex.bind(this);
  }

  componentDidMount(): void {
    this.callMenuIndex();
  }

  callMenuIndex() {
    MenuIndex().then((res) => {
      this.setState({
        menuList: res?.data?.data,
      });
    });
  }
  render() {
    return (
      <div className="flex justify-start h-screen overflow-y-hidden">
        <div className="dashboard-menu md:w-2/12 overflow-y-auto md:py-5 py-1 md:px-6 px-1.5 border-r border-gray-200">
          <div className="flex gap-x-4 items-center">
            <div className="rounded-full h-8 w-8 bg-gray-400"></div>
            <p className=" font-intersemibold text-sm ">Property ERP</p>
          </div>
          <div className="mt-7 w-full flex flex-col gap-2">
            {this.state.menuList?.map((item) => {
              return (
                <div key={item.id}>
                  {!item.parent_id && item.child.length < 1 && (
                    <NavLink to={item.url}>
                      {({ isActive }) => (
                        <div
                          className={`${
                            isActive ? "text-primary-dark " : ""
                          } flex gap-x-3 items-center w-full font-intersemibold`}
                        >
                          <Icon
                            icon={item.icon ?? "home_simple"}
                            width={20}
                            height={20}
                            className="my-auto"
                            color={isActive ? "#333fff" : "#374151"}
                          />
                          <p className="my-auto text-sm">{item.title}</p>
                        </div>
                      )}
                    </NavLink>
                  )}
                  {!item.parent_id && item.child.length > 0 && (
                    <div className="flex flex-col gap-2 ">
                      <div
                        aria-hidden="true"
                        onClick={() => {
                          this.setState((prevState: any) => ({
                            menuList: this.state.menuList?.map((x) => {
                              if (x.id === item.id) {
                                return { ...x, show: !item.show };
                              } else return x;
                            }),
                          }));
                        }}
                        className="cursor-pointer flex flex-row mb-1 items-center gap-3 font-intersemibold"
                      >
                        <Icon
                          icon={item.icon ?? "home_simple"}
                          width={20}
                          height={20}
                          color={"#1f2937"}
                        />
                        <p className="mr-auto text-sm">{item.title}</p>
                        {!item.show && (
                          <Icon
                            icon={"arrow_left_simple"}
                            width={20}
                            height={20}
                            color={"#1f2937"}
                          />
                        )}
                        {item.show && (
                          <Icon
                            icon={"arrow_down_simple"}
                            width={20}
                            height={20}
                            color={"#1f2937"}
                          />
                        )}
                      </div>
                      {item.show &&
                        item.child?.map((ch: any) => {
                          return (
                            <NavLink key={ch.id} to={item.url + ch.url}>
                              {({ isActive }) => (
                                <div
                                  className={`${
                                    isActive ? "text-primary-dark" : ""
                                  } pr-3 pl-5 flex items-center gap-x-3 font-intersemibold`}
                                >
                                  <Icon
                                    icon={ch.icon ?? "home_simple"}
                                    width={20}
                                    height={20}
                                    color={isActive ? "#333fff" : "#374151"}
                                  />
                                  <p className="text-sm">{ch.title}</p>
                                </div>
                              )}
                            </NavLink>
                          );
                        })}
                    </div>
                  )}
                </div>
              );
            })}
          </div>
        </div>
        <div className="md:w-10/12 overflow-y-auto px-7 pt-5 bg-white">
          <Outlet></Outlet>
        </div>
      </div>
    );
  }
}

export default Dashboard;
