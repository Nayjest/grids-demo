<?php namespace App\Http\Controllers;

use App\User;
use Grids;
use HTML;
use Illuminate\Support\Facades\Config;
use Nayjest\Grids\Components\Base\RenderableRegistry;
use Nayjest\Grids\Components\ColumnHeadersRow;
use Nayjest\Grids\Components\ColumnsHider;
use Nayjest\Grids\Components\CsvExport;
use Nayjest\Grids\Components\ExcelExport;
use Nayjest\Grids\Components\Filters\DateRangePicker;
use Nayjest\Grids\Components\FiltersRow;
use Nayjest\Grids\Components\HtmlTag;
use Nayjest\Grids\Components\Laravel5\Pager;
use Nayjest\Grids\Components\OneCellRow;
use Nayjest\Grids\Components\RecordsPerPage;
use Nayjest\Grids\Components\RenderFunc;
use Nayjest\Grids\Components\ShowingRecords;
use Nayjest\Grids\Components\TFoot;
use Nayjest\Grids\Components\THead;
use Nayjest\Grids\Components\TotalsRow;
use Nayjest\Grids\DbalDataProvider;
use Nayjest\Grids\EloquentDataProvider;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\FilterConfig;
use Nayjest\Grids\Grid;
use Nayjest\Grids\GridConfig;

class DemoController extends Controller
{

    public function getExample1()
    {
        $cfg = [
            'src' => 'App\User',
            'columns' => [
                'id',
                'name',
                'email',
                'country'
            ]
        ];
        $grid = Grids::make($cfg);
        $text = "<h1>Basic grid example</h1>";
        return view('demo.default', compact('grid', 'text'));
    }

    public function getExample2()
    {
        $text = "<h1>Loading grid config</h1>";
        $grid = Grids::make(Config::get('grids.example2'));
        return view('demo.default', compact('grid', 'text'));
    }

    public function getExample3()
    {
        $cfg = (new GridConfig())
            ->setDataProvider(
                new EloquentDataProvider(
                    (new User)->newQuery()
                )
            )
            ->setColumns([
                new FieldConfig('id'),
                new FieldConfig('name'),
                new FieldConfig('email'),
                new FieldConfig('country'),
            ]);
        $grid = new Grid($cfg);
        $text = "<h1>Constructing grid programmatically</h1>";
        return view('demo.default', compact('grid', 'text'));
    }

    public function getExample4()
    {
        $grid = new Grid(
            (new GridConfig)
                ->setDataProvider(
                    new EloquentDataProvider(User::query())
                )
                ->setName('example_grid4')
                ->setPageSize(15)
                ->setColumns([
                    (new FieldConfig)
                        ->setName('id')
                        ->setLabel('ID')
                        ->setSortable(true)
                        ->setSorting(Grid::SORT_ASC)
                    ,
                    (new FieldConfig)
                        ->setName('name')
                        ->setLabel('Name')
                        ->setCallback(function ($val) {
                            return "<span class='glyphicon glyphicon-user'></span>{$val}";
                        })
                        ->setSortable(true)
                        ->addFilter(
                            (new FilterConfig)
                                ->setOperator(FilterConfig::OPERATOR_LIKE)
                        )
                    ,
                    (new FieldConfig)
                        ->setName('email')
                        ->setLabel('Email')
                        ->setSortable(true)
                        ->setCallback(function ($val) {
                            $icon = '<span class="glyphicon glyphicon-envelope"></span>&nbsp;';
                            return
                                '<small>'
                                . $icon
                                . HTML::link("mailto:$val", $val)
                                . '</small>';
                        })
                        ->addFilter(
                            (new FilterConfig)
                                ->setOperator(FilterConfig::OPERATOR_LIKE)
                        )
                    ,
                    (new FieldConfig)
                        ->setName('phone_number')
                        ->setLabel('Phone')
                        ->setSortable(true)
                        ->addFilter(
                            (new FilterConfig)
                                ->setOperator(FilterConfig::OPERATOR_LIKE)
                        )
                    ,
                    (new FieldConfig)
                        ->setName('country')
                        ->setLabel('Country')
                        ->setSortable(true)
                    ,
                    (new FieldConfig)
                        ->setName('company')
                        ->setLabel('Company')
                        ->setSortable(true)
                        ->addFilter(
                            (new FilterConfig)
                                ->setOperator(FilterConfig::OPERATOR_LIKE)
                        )
                    ,
                    (new FieldConfig)
                        ->setName('birthday')
                        ->setLabel('Birthday')
                        ->setSortable(true)
                    ,
                    (new FieldConfig)
                        ->setName('posts_count')
                        ->setLabel('Posts')
                        ->setSortable(true)
                    ,
                    (new FieldConfig)
                        ->setName('comments_count')
                        ->setLabel('Comments')
                        ->setSortable(true)
                    ,
                ])
                ->setComponents([
                    (new THead)
                        ->setComponents([
                            (new ColumnHeadersRow),
                            (new FiltersRow)
                                ->addComponents([
                                    (new RenderFunc(function () {
                                        return HTML::style('js/daterangepicker/daterangepicker-bs3.css')
                                        . HTML::script('js/moment/moment-with-locales.js')
                                        . HTML::script('js/daterangepicker/daterangepicker.js')
                                        . "<style>
                                                .daterangepicker td.available.active,
                                                .daterangepicker li.active,
                                                .daterangepicker li:hover {
                                                    color:black !important;
                                                    font-weight: bold;
                                                }
                                           </style>";
                                    }))
                                        ->setRenderSection('filters_row_column_birthday'),
                                    (new DateRangePicker)
                                        ->setName('birthday')
                                        ->setRenderSection('filters_row_column_birthday')
                                        ->setDefaultValue(['1990-01-01', date('Y-m-d')])
                                ])
                            ,
                            (new OneCellRow)
                                ->setRenderSection(RenderableRegistry::SECTION_END)
                                ->setComponents([
                                    new RecordsPerPage,
                                    new ColumnsHider,
                                    (new CsvExport)
                                        ->setFileName('my_report' . date('Y-m-d'))
                                    ,
                                    new ExcelExport(),
                                    (new HtmlTag)
                                        ->setContent('<span class="glyphicon glyphicon-refresh"></span> Filter')
                                        ->setTagName('button')
                                        ->setRenderSection(RenderableRegistry::SECTION_END)
                                        ->setAttributes([
                                            'class' => 'btn btn-success btn-sm'
                                        ])
                                ])

                        ])
                    ,
                    (new TFoot)
                        ->setComponents([
                            (new TotalsRow(['posts_count', 'comments_count'])),
                            (new TotalsRow(['posts_count', 'comments_count']))
                                ->setFieldOperations([
                                    'posts_count' => TotalsRow::OPERATION_AVG,
                                    'comments_count' => TotalsRow::OPERATION_AVG,
                                ])
                            ,
                            (new OneCellRow)
                                ->setComponents([
                                    new Pager,
                                    (new HtmlTag)
                                        ->setAttributes(['class' => 'pull-right'])
                                        ->addComponent(new ShowingRecords)
                                    ,
                                ])
                        ])
                    ,
                ])
        );
        $grid = $grid->render();
        return view('demo.default', compact('grid'));
    }

    public function getExample5()
    {
        $query = \DB::getDoctrineConnection()->createQueryBuilder();
        $query
            ->select([
                'id',
                'name',
                'email',
                'country',
                'posts_count'
            ])
            ->from('users')
            ->where('posts_count > 40');

        $cfg = (new GridConfig())
            ->setDataProvider(
                new DbalDataProvider($query)
            )
            ->setPageSize(5)
            ->setColumns([
                new FieldConfig('id'),
                new FieldConfig('name'),
                new FieldConfig('email'),
                new FieldConfig('country'),
                new FieldConfig('posts_count'),
            ]);
        $grid = new Grid($cfg);
        $text = '<h1>Grid with DbalDataProvider</h1>';
        return view('demo.default', compact('grid', 'text'));
    }

    public function getExample6()
    {

        $cfg1 = (new GridConfig())
            ->setName('bf1990')
            ->setDataProvider(
                new EloquentDataProvider(
                    (new User)
                        ->newQuery()
                        ->where('birthday', '<', '1990-01-01')
                )
            )
            ->setPageSize(5)
            ->setColumns([
                new FieldConfig('id'),
                (new FieldConfig('name'))
                    ->addFilter(new FilterConfig)
                    ->setSortable(true)
                ,
                (new FieldConfig('birthday'))
                    ->setSortable(true)
            ])
            ->setComponents([
                (new THead)
                    ->getComponentByName(FiltersRow::NAME)
                    ->addComponent(
                        (new HtmlTag)
                            ->setTagName('button')
                            ->setAttributes([
                                'type' => 'submit',
                                'class' => 'btn btn-success btn-small'
                            ])
                            ->addComponent(new RenderFunc(function() {
                                return '<i class="glyphicon glyphicon-refresh"></i> Filter';
                            }))
                            ->setRenderSection('filters_row_column_birthday')
                    )
                    ->addComponent(
                        (new ExcelExport)
                            ->setFileName('users_before1990')
                            ->setRenderSection('filters_row_column_birthday')

                    )
                    ->getParent()
                ,
                new TFoot
            ]);
        ;


        $grid1 = (new Grid($cfg1))->render();

        $cfg2 = (new GridConfig())
            ->setName('af1990')
            ->setDataProvider(
                new EloquentDataProvider(
                    (new User)
                        ->newQuery()
                        ->where('birthday', '>=', '1990-01-01')
                )
            )
            ->setPageSize(5)
            ->setColumns([
                new FieldConfig('id'),
                (new FieldConfig('name'))
                    ->addFilter(
                        (new FilterConfig)
                        ->setOperator(FilterConfig::OPERATOR_LIKE)
                    )
                    ->setSortable(true)
                ,
                (new FieldConfig('birthday'))
                    ->setSortable(true)
            ])
            ->setComponents([
                (new THead)
                    ->getComponentByName(FiltersRow::NAME)
                    ->addComponent(
                        (new HtmlTag)
                            ->setTagName('button')
                            ->setAttributes([
                                'type' => 'submit',
                                'class' => 'btn btn-success btn-small'
                            ])
                            ->addComponent(new RenderFunc(function() {
                                return '<i class="glyphicon glyphicon-refresh"></i> Filter';
                            }))
                            ->setRenderSection('filters_row_column_birthday')
                    )
                    ->addComponent(
                        (new ExcelExport)
                            ->setFileName('users_after1990')
                            ->setRenderSection('filters_row_column_birthday')

                    )
                    ->getParent()
                ,
                new TFoot
            ]);
        $grid2 = (new Grid($cfg2))->render();

        $text = "<h1>Multiple grids on same page</h1>";

        return view('demo.default', [
            'text' => $text,
            'grid' =>
                '<h2>Users born before 1990</h2>'
                . $grid1
                .'<h2>Users born after 1990</h2>'
                . $grid2
        ]);

    }

}
