import { Component, ReactNode, Suspense } from "react";
import {
  RouterInterface,
  withRouterInterface,
} from "../../../../router/interface";
import { Button, Dialog, Table } from "@angelineuniverse/design";
import { tables, remove } from "./controller";
import { numberFormat } from "../../../../service/helper";
import clsx from "clsx";

class TypeUnit extends Component<RouterInterface> {
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
            className="max-w-full overflow-auto"
            classNameTable="w-[120%]"
            useHeadline
            title="Atur semua Type Unit"
            description="Tambahkan semua type yang anda gunakan untuk mengelompokan Unit"
            createTitle="Tambah Type Unit"
            create={async () => {
              await this.props.navigate("/master/unit/type/add");
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
            edit={async (event) => {
              await this.props.navigate("/master/unit/type/show/" + event.id);
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
                {key === "status" && (
                  <p
                    className={clsx(
                      "rounded-xl w-fit px-3 py-1 mx-auto",
                      `bg-${row.unit_status.color}-100 text-${row.unit_status.color}-600 font-intersemibold`,
                      `border border-${row.unit_status.color}-400`
                    )}
                  >
                    {row.unit_status.title}
                  </p>
                )}
                {key === "price" && (
                  <p className="text-center font-intermedium">
                    {numberFormat(row.price)}
                  </p>
                )}
                {key === "land" && (
                  <p className="text-center font-medium">
                    {row.width_land} / {row.long_land}
                  </p>
                )}
                {key === "build" && (
                  <p className="text-center font-medium">
                    {row.width_build} / {row.long_build}
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

export default withRouterInterface(TypeUnit);
