import { Component, ReactNode, Suspense } from "react";
import {
  RouterInterface,
  withRouterInterface,
} from "../../../../router/interface";
import { Button, Dialog, Table } from "@angelineuniverse/design";
import { tables, remove } from "./controller";
import clsx from "clsx";

class ListUnit extends Component<RouterInterface> {
  state: Readonly<{
    index: any;
    detail: any;
    popDelete: boolean;
    loading: boolean;
  }>;
  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      index: {
        column: [],
        data: undefined,
        property: undefined,
      },
      popDelete: false,
      loading: false,
      detail: undefined,
    };
    this.callTable = this.callTable.bind(this);
  }
  componentDidMount(): void {
    this.callTable();
  }
  callTable(page?: object) {
    this.setState({
      index: {
        column: [],
        data: undefined,
        property: undefined,
      },
    });
    tables(page).then((res) => {
      this.setState({
        index: {
          column: res.data.column,
          data: res.data.data,
          property: res.data.property,
        },
      });
    });
  }
  async deleted() {
    this.setState({
      loading: true,
    });
    await remove(this.state.detail?.id)
      .then((res) => {
        this.callTable();
        this.setState({
          popDelete: false,
          loading: false,
        });
      })
      .catch(() => {
        this.setState({
          loading: false,
        });
      });
  }

  render(): ReactNode {
    return (
      <div className="">
        <Suspense>
          <Table
            useCreate
            useHeadline
            title="Atur semua Unit"
            description="Tambahkan semua unit yang anda miliki"
            createTitle="Tambah Unit Baru"
            create={() => {
              this.props.navigate("/master/unit/list/add");
            }}
            changePage={(page: number) => {
              this.callTable({ page: page });
            }}
            onSort={(type, key) => {
              this.callTable({
                type: type,
                key: key,
              });
            }}
            edit={(event) => {
              this.props.navigate("/master/unit/list/show/" + event.id);
            }}
            delete={(event) => {
              this.setState({
                detail: event,
                popDelete: true,
              });
            }}
            column={this.state.index.column}
            data={this.state.index.data}
            property={this.state.index.property}
            custom={(row: any, key: string) => (
              <div>
                {key === "type" && (
                  <div className="text-xs">
                    <p className=" font-intersemibold uppercase">
                      {row.type?.title}
                    </p>
                    <p className="mb-1.5 text-[10px] pb-1 border-b border-blue-600">
                      {row.type?.descriptions}
                    </p>
                    <div className="flex gap-x-2">
                      <span className="font-intersemibold">
                        {row.type?.unit_class?.title}
                      </span>
                      <span className=" font-intersemibold text-[11px] text-red-800">
                        {new Intl.NumberFormat("id-ID", {
                          style: "currency",
                          currency: "IDR",
                          minimumFractionDigits: 0,
                        }).format(row.type?.price)}
                      </span>
                    </div>
                    <div className=" flex gap-x-2">
                      <span className=" text-[11px]">
                        Bangunan : {row.type?.long_build}/
                        {row.type?.width_build}
                      </span>
                      <span className=" text-[11px]">
                        Tanah : {row.type?.long_land}/{row.type?.width_land}
                      </span>
                    </div>
                  </div>
                )}
                {key === "status" && (
                  <p
                    className={clsx(
                      "rounded-xl w-fit px-3 py-1 mx-auto",
                      `bg-${row.status?.color}-100 text-${row.status?.color}-600 font-intersemibold`,
                      `border border-${row.status?.color}-400`
                    )}
                  >
                    {row.status?.title}
                  </p>
                )}
              </div>
            )}
          />
          <Dialog
            onOpen={this.state.popDelete}
            title="Hapus Data"
            size="small"
            classHeading="uppercase text-red-500"
            useHeading
            onClose={() => {
              this.setState({
                popDelete: false,
              });
            }}
          >
            <p className=" text-xs font-interregular">
              Saat anda menghapus item yang dipilih, semua informasi yang
              terdapat pada system akan dihapus seluruhnya. Ingin melanjutkan ?
            </p>
            <div className="flex justify-end gap-x-5 mt-6">
              <Button
                title="Kembali"
                theme="transparent"
                size="small"
                onClick={() => {
                  this.setState({
                    popDelete: false,
                  });
                }}
                width="block"
              />
              <Button
                title="Hapus Data"
                theme="error"
                size="small"
                width="block"
                isLoading={this.state.loading}
                onClick={() => this.deleted()}
              />
            </div>
          </Dialog>
        </Suspense>
      </div>
    );
  }
}

export default withRouterInterface(ListUnit);
