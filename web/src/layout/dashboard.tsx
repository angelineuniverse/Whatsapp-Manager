import { Component } from "react";
import { NavLink, Outlet } from "react-router-dom";
import Icon from "../component/icon/icon";
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
        menuList: res,
      });
    });
  }
  render() {
    return (
      <div className="flex justify-start h-screen bg-slate-100 overflow-y-hidden">
        <div className="dashboard-menu md:w-[13%] overflow-y-auto">
          <div className="w-full h-full bg-white">
            <div className="p-3 flex gap-x-4 items-center">
              <div className="rounded-full h-8 w-8 bg-gray-400"></div>
              <p className=" font-intersemibold text-xs ">WhatsApp Manager</p>
            </div>
            <div className="mt-10 w-full px-2 flex flex-col gap-y-2">
              {this.state.menuList?.map((item) => {
                return (
                  <div key={item.id}>
                    {!item.child && (
                      <NavLink to={item.path}>
                        {({ isActive }) => (
                          <div
                            className={`${
                              isActive ? "bg-gray-800 text-white" : ""
                            } px-3 pt-1.5 pb-2 rounded-lg flex items-center gap-x-2 font-intermedium`}
                          >
                            <Icon
                              icon={item.icon ?? "home_simple"}
                              width={20}
                              height={20}
                              color={isActive ? "#fff" : "#1f2937"}
                            />
                            <p>{item.title}</p>
                          </div>
                        )}
                      </NavLink>
                    )}
                    {item.child && (
                      <div>
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
                          className={`px-3 pt-1.5 pb-2 mb-1 cursor-pointer rounded-lg flex items-center justify-end gap-x-2 w-full font-intermedium`}
                        >
                          <Icon
                            icon={item.icon ?? "home_simple"}
                            width={20}
                            height={20}
                            color={"#1f2937"}
                          />
                          <p className="mr-auto">{item.title}</p>
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
                              <NavLink key={ch.id} to={item.path + ch.path}>
                                {({ isActive }) => (
                                  <div
                                    className={`${
                                      isActive ? "bg-gray-800 text-white" : ""
                                    } pr-3 pl-6 pt-1.5 pb-2 rounded-lg flex items-center gap-x-2 font-intermedium`}
                                  >
                                    <Icon
                                      icon={ch.icon ?? "home_simple"}
                                      width={20}
                                      height={20}
                                      color={isActive ? "#fff" : "#1f2937"}
                                    />
                                    <p>{ch.title}</p>
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
        </div>
        <div className="md:w-[87%] overflow-y-auto px-7 pt-5">
          <Outlet></Outlet>
        </div>
      </div>
    );
  }
}

export default Dashboard;
