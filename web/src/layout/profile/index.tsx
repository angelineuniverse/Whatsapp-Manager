import { Component, ReactNode, Suspense } from "react";
import { profile, profile_log } from "./controller";
import {
  Button,
  Form,
  Skeleton,
  Tab,
  TabItem,
  Table,
} from "@angelineuniverse/design";
import clsx from "clsx";

class Index extends Component {
  state: Readonly<{
    selected: string | number;
    profile: any;
    log: any;
    form: Array<any> | undefined;
  }>;
  constructor(props: any) {
    super(props);
    this.state = {
      selected: 1,
      profile: undefined,
      log: {
        column: [],
        data: undefined,
        property: undefined,
      },
      form: undefined,
    };
    this.callProfile = this.callProfile.bind(this);
  }
  componentDidMount(): void {
    this.callProfile();
    this.callProfileLog();
  }
  async callProfile() {
    return await profile().then((res) => {
      this.setState({
        profile: res.data?.data?.detail,
        form: res.data?.data?.form,
      });
    });
  }
  async callProfileLog(params?: object) {
    return await profile_log(params).then((res) => {
      this.setState({
        log: {
          data: res.data?.data,
          column: res.data?.column,
          property: res.data?.property,
        },
      });
    });
  }
  render(): ReactNode {
    return (
      <div className="pb-9">
        <p className="mb-14 font-interbold text-xl">Informasi Pribadi</p>
        <div className="mb-16 md:grid md:grid-cols-6 md:gap-x-10 w-full">
          <div className="relative w-full md:col-span-1">
            {!this.state.profile && (
              <Suspense>
                <Skeleton
                  type="custom"
                  className="w-32 h-32 mb-5 max-w-32 mx-auto rounded-full"
                />
                <Skeleton type="custom" className="w-full h-4" />
                <Skeleton type="custom" className="w-20 h-4" />
              </Suspense>
            )}
            {this.state.profile && (
              <div className="text-center">
                <img
                  src={this.state.profile?.link}
                  alt="profile"
                  className=" w-32 h-32 mx-auto border border-gray-400 text-center rounded-full mb-5 bg-gray-500"
                />
                <p className=" font-interbold">{this.state.profile?.name}</p>
                <p className="text-xs font-interregular">
                  {this.state.profile?.email}
                </p>
              </div>
            )}
          </div>
          <div className="relative w-full md:col-span-5">
            <Suspense>
              <Form
                className="grid grid-cols-3 gap-4"
                classNameLoading="grid grid-cols-3 gap-3"
                lengthLoading={5}
                form={this.state.form}
              />
              <Button
                className="flex justify-end"
                title="Update Profile"
                theme="success"
                width="block"
                size="small"
              />
            </Suspense>
          </div>
        </div>
        <Suspense>
          <Tab valueSelected={this.state.selected} tabDirection="horizontal">
            <TabItem label="Riwayat Aktivitas" value={1}>
              {this.state.log && (
                <div className="mt-5">
                  <Table
                    title="Semua Riwayat Aktivitas Anda"
                    column={this.state.log?.column}
                    data={this.state.log?.data}
                    property={this.state.log?.property}
                    useCreate={false}
                    useHeadline
                    changePage={(page: number) => {
                      this.callProfileLog({ page: page });
                    }}
                    custom={(row: any, key: string) => (
                      <div>
                        {key === "action" && (
                          <p
                            className={clsx(
                              "rounded-xl w-fit text-[11px] px-2.5 py-1 mx-auto",
                              `bg-${row.action?.color}-100 text-${row.action?.color}-600 font-intersemibold`,
                              `border border-${row.action?.color}-400`
                            )}
                          >
                            {row.action?.action}
                          </p>
                        )}
                      </div>
                    )}
                  />
                </div>
              )}
            </TabItem>
          </Tab>
        </Suspense>
      </div>
    );
  }
}

export default Index;
